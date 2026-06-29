<?php

use App\Notifications\WorkflowNotification;

it('serializes workflow notification data for the database channel', function () {
    $notification = new WorkflowNotification(
        'RTL Diverifikasi',
        'RTL Anda telah diverifikasi.',
        'http://localhost/rtl/1',
        'bx-check-circle',
        'success',
    );

    expect($notification->via(new stdClass))->toBe(['database'])
        ->and($notification->toArray(new stdClass))->toBe([
            'title' => 'RTL Diverifikasi',
            'pesan' => 'RTL Anda telah diverifikasi.',
            'url' => 'http://localhost/rtl/1',
            'icon' => 'bx-check-circle',
            'color' => 'success',
        ]);
});
