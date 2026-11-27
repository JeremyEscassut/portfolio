const fs = require('fs');
const path = require('path');

function loadJson(file) {
    try {
        return JSON.parse(fs.readFileSync(file, 'utf8'));
    } catch (e) {
        return {};
    }
}

const enPath = path.resolve(__dirname, '..', 'resources', 'lang', 'en.json');
const frPath = path.resolve(__dirname, '..', 'resources', 'lang', 'fr.json');

const en = loadJson(enPath);
const fr = loadJson(frPath);

const enKeys = Object.keys(en).sort();
const frKeys = Object.keys(fr).sort();

const missingInFr = enKeys.filter(k => !(k in fr));
const extraInFr = frKeys.filter(k => !(k in en));

console.log('Missing keys in fr.json:');
missingInFr.forEach(k => console.log('  ', k));
console.log('\nExtra keys in fr.json (not present in en.json):');
extraInFr.forEach(k => console.log('  ', k));

if (missingInFr.length === 0 && extraInFr.length === 0) {
    console.log('\nAll keys are synchronized.');
} else {
    console.log('\nUse this output to keep translation files in sync.');
}
