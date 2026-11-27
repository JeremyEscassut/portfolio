<?php

namespace Tests\Feature;

use Tests\TestCase;

class CarouselAndVideoBehaviorTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function home_page_contains_the_scroll_snap_carousel_and_the_video_dialog_hooks()
    {
        $response = $this->get(route('home', ['locale' => 'fr']));

        $response->assertOk();
        $response->assertSee('data-carousel', false);
        $response->assertSee('class="carousel__track" tabindex="0"', false);
        $response->assertSee('data-direction="previous"', false);
        $response->assertSee('data-direction="next"', false);
        $response->assertSee('data-slide="2"', false);
        $response->assertSee('data-dialog-open="video-dialog"', false);
        $response->assertSee('video/mp4', false);
    }
}
