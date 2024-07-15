<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use Illuminate\Support\Facades\Hash;

class DataMonitorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Karmanto',
            'email' => 'karmanto.s@gmail.com',
            'password' => Hash::make('Qweytr123654$AL'),
            'role' => 'superadmin',
        ]);

        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('@@admin789123'),
            'role' => 'superadmin',
        ]);

        $start = Carbon::parse('01 July ' . now()->year)->startOfDay();
        $end = Carbon::parse('11 July ' . now()->year)->endOfDay();

        $interval = new DateInterval('PT1S');
        $period = new DatePeriod($start, $interval, $end);

        $fields = [
            'cbc1', 'cbc2', 'prs1', 'prs2', 'prs3', 'prs4', 'prs5', 'prs6', 'prs7', 'prs8', 
            'dtr1', 'dtr2', 'dtr3', 'dtr4', 'dtr5', 'dtr6', 'dtr7', 'dtr8',
            'spr0', 'spr1', 'spr2', 'spr3', 'spr4', 'spr5', 'spr6', 'spr7', 'spr8', 'spr9',
            'spr10', 'spr11', 'spr12', 'spr13', 'spr14', 'spr15', 'spr16', 'spr17', 'spr18', 'spr19',
            'spr20', 'spr21', 'spr22', 'spr23', 'spr24', 'spr25', 'spr26', 'spr27', 'spr28', 'spr29',
            'spr30', 'spr31', 'spr32', 'spr33', 'spr34', 'spr35', 'spr36', 'spr37', 'spr38', 'spr39',
            'spr40', 'spr41', 'spr42', 'spr43', 'spr44', 'spr45'
        ];

        $data = [];
        $cbc1Counter = 0;
        $cbc2Counter = 0;

        $fieldCounters = [];
        $fieldValues = [];
        $runSeconds = array_fill_keys($fields, 0);

        foreach ($fields as $field) {
            $fieldCounters[$field] = rand(300, 86400);
            $fieldValues[$field] = rand(0, 1);
        }

        $recordCount = 0;
        $lastRecord = null;

        $periodArray = iterator_to_array($period);
        $lastTime = end($periodArray);

        foreach ($period as $time) {
            $record = ['created_at' => $time];

            foreach ($fields as $field) {
                if ($fieldCounters[$field] > 0) {
                    $record[$field] = $fieldValues[$field];
                    $fieldCounters[$field]--;
                } else {
                    $fieldValues[$field] = 1 - $fieldValues[$field];
                    $fieldCounters[$field] = rand(300, 86400);
                    $record[$field] = $fieldValues[$field];
                }

                if ($record[$field] == 1) {
                    $runSeconds[$field]++;
                }
            }

            if ($cbc1Counter <= 0 && $cbc2Counter <= 0) {
                if (rand(0, 1)) {
                    $cbc1Counter = rand(43200, 172800);
                    $record['cbc1'] = 1;
                } else {
                    $cbc2Counter = rand(43200, 172800);
                    $record['cbc2'] = 1;
                }
            }

            if ($cbc1Counter > 0) {
                $record['cbc1'] = 1;
                $cbc1Counter--;
            } else {
                $record['cbc1'] = 0;
            }

            if ($cbc2Counter > 0) {
                $record['cbc2'] = 1;
                $cbc2Counter--;
            } else {
                $record['cbc2'] = 0;
            }

            $currentRecordWithoutCreatedAt = $record;
            $lastRecordWithoutCreatedAt = $lastRecord;

            unset($currentRecordWithoutCreatedAt['created_at']);
            unset($lastRecordWithoutCreatedAt['created_at']);

            if ($lastRecord === null || json_encode($currentRecordWithoutCreatedAt) !== json_encode($lastRecordWithoutCreatedAt)) {
                $data[] = $record;
                $lastRecord = $record;
                $recordCount++;
            }

            if (count($data) >= 1000) {
                DB::table('data_monitor')->insert($data);
                $data = [];
                echo "Inserted 1000 records up to {$record['created_at']}.\n";
            }

            if ($time == $lastTime) {
                $allZero = true;
                foreach ($fields as $field) {
                    if ($lastRecord && $lastRecord[$field] != 0) {
                        $allZero = false;
                        break;
                    }
                }
                if (!$allZero) {
                    foreach ($fields as $field) {
                        $record[$field] = 0;
                    }
                    $data[] = $record;
                }
            }
        }

        if (!empty($data)) {
            DB::table('data_monitor')->insert($data);
            echo "Inserted remaining records up to {$record['created_at']}.\n";
        }

        DB::table('data_runtime')->insert($runSeconds);

        echo "Seeding completed. Total records inserted: {$recordCount}.\n";
    }
}