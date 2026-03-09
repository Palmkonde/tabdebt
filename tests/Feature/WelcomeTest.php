<?php

it('displays the welcome page', function () {
    $this->get('/')->assertSuccessful();
});

it('is accessible without authentication', function () {
    $this->assertGuest();

    $this->get('/')->assertSuccessful();
});
