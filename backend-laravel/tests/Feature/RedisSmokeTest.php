<?php

use Illuminate\Support\Facades\Redis;

it('interacts with redis', function () {
    Redis::set('smoke', 'ok');
    expect(Redis::get('smoke'))->toBe('ok');
})->group('redis');
