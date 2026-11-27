<?php

namespace Tests\Feature;

use Tests\TestCase;

class CarouselAndVideoBehaviorTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function home_page_contains_the_carousel_markup_with_swipe_and_video_modal_hooks()
    {
        $response = $this->get(route('home', ['locale' => 'fr']));

        $response->assertOk();
        $response->assertSee('x-data="{ videoOpen: false, slides:', false);
        $response->assertSee('@touchstart="handleTouchStart($event)"', false);
        $response->assertSee('@touchmove="handleTouchMove($event)"', false);
        $response->assertSee('@touchend="handleTouchEnd()"', false);
        $response->assertSee('@pointerdown="handlePointerDown($event)"', false);
        $response->assertSee('@pointermove="handlePointerMove($event)"', false);
        $response->assertSee('@pointerup="handlePointerUp()"', false);
        $response->assertSee('x-show="videoOpen"', false);
        $response->assertSee('video/mp4', false);
    }
}
