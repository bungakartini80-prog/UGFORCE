<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $floors = [
            1 => ['j114', 'j115a', 'j115b', 'j116', 'j118', 'j119', 'j1110', 'j1111'],
            2 => ['j123', 'j124', 'j127', 'j128', 'j129', 'j1210', 'j1211', 'j1212', 'j1215b', 'j1216a', 'j1216b', 'j1217', 'j1219', 'j1220'],
            3 => ['j133a', 'j133b', 'j137', 'j138', 'j139', 'j1310', 'j1311', 'j1312', 'j1315b', 'j1316a', 'j1316b', 'j1317', 'j1318', 'j1319'],
            4 => ['j143', 'j144', 'j147', 'j148', 'j149', 'j1410', 'j1411', 'j1412', 'j1415', 'j1416a', 'j1416b', 'j1417', 'j1419', 'j1420', 'j1424', 'j1425'],
            5 => ['j153', 'j154', 'j155', 'j157', 'j158', 'j159', 'j1510', 'j1511', 'j1512', 'j1515a', 'j1515b', 'j1516a', 'j1516b', 'j1518', 'j1519', 'j1523', 'j1524'],
            6 => ['j161', 'j162', 'j163', 'j164', 'j1611', 'j1612', 'j1613', 'j1614', 'j1615', 'j1616']
        ];

        foreach ($floors as $floor => $rooms) {
            foreach ($rooms as $roomName) {
                Room::updateOrCreate(
                    ['name' => ucfirst($roomName)],
                    [
                        'lantai' => $floor,
                        'capacity' => 40,
                        'description' => 'Fasilitas standard kuliah (AC, Proyektor, Papan Tulis)',
                        'status' => 'available'
                    ]
                );
            }
        }
    }
}