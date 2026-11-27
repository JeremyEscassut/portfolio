<?php

namespace Tests\Feature;

use Tests\TestCase;

class CvPageTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function cv_page_loads_with_pdf_links_and_preview_frame()
    {
        $response = $this->get(route('cv', ['locale' => 'fr']));

        $response->assertOk();
        $response->assertSee('Curriculum Vitae', false);
        $response->assertSee('Ouvrir le CV (nouvel onglet)', false);
        $response->assertSee('Télécharger le CV', false);
        $response->assertSee('Aperçu du CV', false);
        $response->assertSee('ESCASSUT_JEREMY_-_CV_', false);
        $response->assertSee('/cv/', false);
        $response->assertSee('<iframe', false);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function cv_page_exposes_accessible_preview_markup()
    {
        $response = $this->get(route('cv', ['locale' => 'en']));

        $response->assertOk();
        $response->assertSee('title="CV preview"', false);
        $response->assertSee('aria-label="CV preview (PDF)"', false);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function home_page_renders_the_cv_selector_modal_markup()
    {
        $response = $this->get(route('home', ['locale' => 'fr']));

        $response->assertOk();
        $response->assertSee('id="cv-selector-modal"', false);
        $response->assertSee('window.openCvSelectorModal()', false);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function home_page_includes_the_product_owner_cv_option_in_the_modal()
    {
        $response = $this->get(route('home', ['locale' => 'fr']));

        $response->assertOk();
        $response->assertSee('Product Owner', false);
        $response->assertSee('ESCASSUT_JEREMY_-_CV_Product_Owner_-_Transformation_digitale.pdf', false);
    }
}
