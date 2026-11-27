const fs = require('fs');
const path = require('path');

function walk(dir, filelist = []) {
    const files = fs.readdirSync(dir);
    files.forEach(file => {
        const filepath = path.join(dir, file);
        const stat = fs.statSync(filepath);
        if (stat.isDirectory()) {
            walk(filepath, filelist);
        } else if (file.endsWith('.blade.php')) {
            filelist.push(filepath);
        }
    });
    return filelist;
}

function extractStringsFromFile(file) {
    const content = fs.readFileSync(file, 'utf8');
    const lines = content.split(/\r?\n/);
    const results = [];

    // Extract text nodes between > and < (naive)
    const textNodeRegex = />[^<>{}]*([^<>{}][^<>{}]*)</g;

    let match;
    while ((match = textNodeRegex.exec(content)) !== null) {
        const raw = match[1].trim();
        if (!raw) continue;
        // discard if contains blade echo or directives
        if (/\{\{\s*|@if|@foreach|@endif|@endforeach/.test(raw)) continue;
        if (raw.length < 2) continue;
        // ignore numbers-only
        if (/^\d+$/.test(raw)) continue;
        results.push({ type: 'text', text: raw });
    }

    // Extract aria-label, title, alt, placeholder attributes
    const attrRegex = /(aria-label|title|alt|placeholder)\s*=\s*\"([^\"]+)\"/g;
    while ((match = attrRegex.exec(content)) !== null) {
        const attr = match[1];
        const val = match[2].trim();
        if (val && val.length > 1) results.push({ type: 'attr', attr, text: val });
    }

    // dedupe
    const unique = [];
    const seen = new Set();
    results.forEach(r => {
        const key = r.type + '||' + r.text;
        if (!seen.has(key)) {
            seen.add(key);
            unique.push(r);
        }
    });

    // map with file and approximate line number (search first occurrence)
    return unique.map(u => {
        const idx = content.indexOf(u.text);
        let line = 1;
        if (idx >= 0) {
            line = content.slice(0, idx).split(/\r?\n/).length;
        }
        return { file, line, ...u };
    });
}

const viewDir = path.resolve(__dirname, '..', 'resources', 'views');
const files = walk(viewDir);
const all = [];
files.forEach(f => {
    try {
        const extracted = extractStringsFromFile(f);
        extracted.forEach(e => all.push(e));
    } catch (e) {
        console.error('failed', f, e.message);
    }
});

const outPath = path.resolve(__dirname, '..', 'tools', 'extracted-strings.json');
fs.writeFileSync(outPath, JSON.stringify(all, null, 2), 'utf8');
console.log('Extracted', all.length, 'unique strings to', outPath);
