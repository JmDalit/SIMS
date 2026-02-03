<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ListStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('list_statuses')->insert([
            ['name' => 'graduating', 'icon' => 'IconProgressCheck', 'type' => 'progress', 'color_id' => 5, 'is_active' => true, 'is_delete' => false],
            ['name' => 'graduated', 'icon' => 'IconCircleCheck', 'type' => 'progress', 'color_id' => 8, 'is_active' => true, 'is_delete' => false],
            ['name' => 'terminated', 'icon' => 'IconCircleX', 'type' => 'progress', 'color_id' => 9, 'is_active' => true, 'is_delete' => false],
            ['name' => 'non-compliance', 'icon' => 'IconExclamationCircle', 'type' => 'progress', 'color_id' => 16, 'is_active' => true, 'is_delete' => false],
            ['name' => 'withdrew', 'icon' => 'IconFlag', 'type' => 'progress', 'color_id' => 52, 'is_active' => true, 'is_delete' => false],
            ['name' => 'ongoing', 'icon' => 'IconDotsCircleHorizontal', 'type' => 'progress', 'color_id' => 13, 'is_active' => true, 'is_delete' => false],
            ['name' => 'deceased', 'icon' => 'IconGrave2', 'type' => 'progress', 'color_id' => 55, 'is_active' => true, 'is_delete' => false],
            ['name' => 'released', 'icon' => 'IconCircleCheck', 'type' => 'benefit status', 'color_id' => 7, 'is_active' => true, 'is_delete' => false],
            ['name' => 'cancelled', 'icon' => 'IconCancel', 'type' => 'benefit status', 'color_id' => 11, 'is_active' => true, 'is_delete' => false],
            ['name' => 'waiting', 'icon' => 'IconClockHour2', 'type' => 'benefit status', 'color_id' => 15, 'is_active' => true, 'is_delete' => false],
            ['name' => 'pending', 'icon' => 'IconCircleDashed', 'type' => 'benefit status', 'color_id' => 41, 'is_active' => true, 'is_delete' => false],
            ['name' => 'good standing', 'icon' => 'IconFileLike', 'type' => 'ongoing', 'color_id' => 6, 'is_active' => true, 'is_delete' => false],
            ['name' => 'continue under probation', 'icon' => 'IconZoomExclamation', 'type' => 'ongoing', 'color_id' => 2, 'is_active' => true, 'is_delete' => false],
            ['name' => 'continue with partial allowance', 'icon' => 'IconMoneybagMinus', 'type' => 'ongoing', 'color_id' => 36, 'is_active' => true, 'is_delete' => false],
            ['name' => 'leave of absence', 'icon' => 'IconCalendarX', 'type' => 'ongoing', 'color_id' => 24, 'is_active' => true, 'is_delete' => false],
            ['name' => 'no report', 'icon' => 'IconFileAlert', 'type' => 'ongoing', 'color_id' => 16, 'is_active' => true, 'is_delete' => false],
            ['name' => 'unknown', 'icon' => 'IconCircle', 'type' => 'progress', 'color_id' => 49, 'is_active' => true, 'is_delete' => false],
            ['name' => 'waiting', 'icon' => 'IconClockHour8', 'type' => 'qualifier', 'color_id' => 13, 'is_active' => true, 'is_delete' => false],
            ['name' => 'deferment', 'icon' => 'IconClockEdit', 'type' => 'qualifier', 'color_id' => 1, 'is_active' => true, 'is_delete' => false],
            ['name' => 'not avail', 'icon' => 'IconCircleX', 'type' => 'qualifier', 'color_id' => 12, 'is_active' => true, 'is_delete' => false],
            ['name' => 'enrolled', 'icon' => 'IconRosetteDiscountCheck', 'type' => 'qualifier', 'color_id' => 7, 'is_active' => true, 'is_delete' => false],
        ]);
    }
}
