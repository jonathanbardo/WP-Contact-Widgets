<?php

namespace WPCW;

final class TestContact extends TestCase {

	function setUp() {

		parent::setUp();

		$this->plugin = new Contact();

	}

	function test_construct() {

		$this->assertEquals( $this->plugin->widget_options['classname'], 'wpcw-widget-contact' );

		$this->assertEquals( $this->plugin->id_base, 'wpcw_contact' );

	}

	function test_form() {

		$this->expectOutputRegex( '/class="wpcw-widget wpcw-widget-contact"/' );
		$this->expectOutputRegex( '/class="customizer_update"/' );

		$this->plugin->form( [] );

	}

	function test_widget() {

		$wp_styles = wp_styles();

		$instance = [
			'title'  => 'test',
			'labels' => [
				'value' => 'yes',
			],
			'map' => [
				'value' => 'yes',
			],
			'address' => [
				'value' => '<br>123 Santa Monica<br>'
			]
		];
		$args = [
			'before_widget' => '<div class="widget wpcw-widget-contact"><div class="widget-content">',
			'after_widget' => '</div><div class="clear"></div></div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		];

		$this->expectOutputRegex( '/<div class="widget wpcw-widget-contact"><div class="widget-content">/' );
		$this->expectOutputRegex( '/<h3 class="widget-title">/' );

		// Check that we sprint the right google url
		$this->expectOutputRegex( '~//www\.google\.com/maps\?q=123\+Santa\+Monica&output=embed~' );

		$this->plugin->widget( $args, $instance );

		// Make sure the JS file is enqueued
		$this->assertContains( 'wpcw', $wp_styles->queue );

	}

}

