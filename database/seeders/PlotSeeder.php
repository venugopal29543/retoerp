<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plot;

class PlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing plots
        Plot::truncate();

        // Plot data from the JSON file
        $plotsData = [
            [
                'plot_id' => '1',
                'display_name' => '1',
                'block' => 'A',
                'coordinates' => [
                    ['x' => 684, 'y' => 3526],
                    ['x' => 998, 'y' => 3504],
                    ['x' => 1012, 'y' => 3852],
                    ['x' => 698, 'y' => 3874]
                ],
                'price' => 850000,
                'area' => 1800,
                'status' => 'available',
                'amenities' => ['Water', 'Electricity', 'Road Access'],
                'layout' => 'Sathenapally Main',
                'layout_id' => 'main_layout'
            ],
            [
                'plot_id' => '2',
                'display_name' => '2',
                'block' => 'A',
                'coordinates' => [
                    ['x' => 2901, 'y' => 3233],
                    ['x' => 3056, 'y' => 3233],
                    ['x' => 3056, 'y' => 3659],
                    ['x' => 2901, 'y' => 3659]
                ],
                'price' => 1200000,
                'area' => 2200,
                'status' => 'available',
                'amenities' => ['Water', 'Electricity', 'Road Access', 'Park View'],
                'layout' => 'Sathenapally Main',
                'layout_id' => 'main_layout'
            ],
            [
                'plot_id' => '3',
                'display_name' => '3',
                'block' => 'A',
                'coordinates' => [
                    ['x' => 3056, 'y' => 3233],
                    ['x' => 3172, 'y' => 3233],
                    ['x' => 3172, 'y' => 3659],
                    ['x' => 3056, 'y' => 3659]
                ],
                'price' => 1250000,
                'area' => 2200,
                'status' => 'available',
                'amenities' => ['Water', 'Electricity', 'Road Access', 'Corner Plot'],
                'layout' => 'Sathenapally Main',
                'layout_id' => 'main_layout'
            ],
            [
                'plot_id' => '4',
                'display_name' => '4',
                'block' => 'A',
                'coordinates' => [
                    ['x' => 3172, 'y' => 3233],
                    ['x' => 3288, 'y' => 3233],
                    ['x' => 3288, 'y' => 3659],
                    ['x' => 3172, 'y' => 3659]
                ],
                'price' => 1180000,
                'area' => 2200,
                'status' => 'booked',
                'amenities' => ['Water', 'Electricity', 'Road Access'],
                'layout' => 'Sathenapally Main',
                'layout_id' => 'main_layout'
            ],
            [
                'plot_id' => '5',
                'display_name' => '5',
                'block' => 'A',
                'coordinates' => [
                    ['x' => 3288, 'y' => 3233],
                    ['x' => 3404, 'y' => 3233],
                    ['x' => 3404, 'y' => 3659],
                    ['x' => 3288, 'y' => 3659]
                ],
                'price' => 1300000,
                'area' => 2200,
                'status' => 'available',
                'amenities' => ['Water', 'Electricity', 'Road Access', 'Wide Road'],
                'layout' => 'Sathenapally Main',
                'layout_id' => 'main_layout'
            ],
            [
                'plot_id' => '6',
                'display_name' => '6',
                'block' => 'B',
                'coordinates' => [
                    ['x' => 3984, 'y' => 3233],
                    ['x' => 4100, 'y' => 3233],
                    ['x' => 4100, 'y' => 3659],
                    ['x' => 3984, 'y' => 3659]
                ],
                'price' => 950000,
                'area' => 1900,
                'status' => 'available',
                'amenities' => ['Water', 'Electricity', 'Road Access'],
                'layout' => 'Sathenapally Main',
                'layout_id' => 'main_layout'
            ],
            [
                'plot_id' => '7',
                'display_name' => '7',
                'block' => 'B',
                'coordinates' => [
                    ['x' => 2668, 'y' => 5507],
                    ['x' => 2894, 'y' => 5512],
                    ['x' => 2891, 'y' => 5666],
                    ['x' => 2674, 'y' => 5662]
                ],
                'price' => 1100000,
                'area' => 2100,
                'status' => 'available',
                'amenities' => ['Water', 'Electricity', 'Road Access', 'Garden View'],
                'layout' => 'Sathenapally Main',
                'layout_id' => 'main_layout'
            ],
            [
                'plot_id' => '8',
                'display_name' => '8',
                'block' => 'B',
                'coordinates' => [
                    ['x' => 2894, 'y' => 5512],
                    ['x' => 3049, 'y' => 5515],
                    ['x' => 3045, 'y' => 5670],
                    ['x' => 2891, 'y' => 5666]
                ],
                'price' => 1150000,
                'area' => 2300,
                'status' => 'available',
                'amenities' => ['Water', 'Electricity', 'Road Access', 'Open Space'],
                'layout' => 'Sathenapally Main',
                'layout_id' => 'main_layout'
            ],
            [
                'plot_id' => '9',
                'display_name' => '9',
                'block' => 'C',
                'coordinates' => [
                    ['x' => 2901, 'y' => 3814],
                    ['x' => 3056, 'y' => 3814],
                    ['x' => 3056, 'y' => 4240],
                    ['x' => 2901, 'y' => 4240]
                ],
                'price' => 1350000,
                'area' => 2300,
                'status' => 'available',
                'amenities' => ['Water', 'Electricity', 'Road Access', 'Premium Location'],
                'layout' => 'Sathenapally Main',
                'layout_id' => 'main_layout'
            ],
            [
                'plot_id' => '10',
                'display_name' => '10',
                'block' => 'C',
                'coordinates' => [
                    ['x' => 3056, 'y' => 3814],
                    ['x' => 3172, 'y' => 3814],
                    ['x' => 3172, 'y' => 4240],
                    ['x' => 3056, 'y' => 4240]
                ],
                'price' => 1400000,
                'area' => 2300,
                'status' => 'available',
                'amenities' => ['Water', 'Electricity', 'Road Access', 'Corner Plot', 'Premium'],
                'layout' => 'Sathenapally Main',
                'layout_id' => 'main_layout'
            ],
            [
                'plot_id' => '11',
                'display_name' => '11',
                'block' => 'C',
                'coordinates' => [
                    ['x' => 3172, 'y' => 3814],
                    ['x' => 3288, 'y' => 3814],
                    ['x' => 3288, 'y' => 4240],
                    ['x' => 3172, 'y' => 4240]
                ],
                'price' => 1380000,
                'area' => 2300,
                'status' => 'booked',
                'amenities' => ['Water', 'Electricity', 'Road Access', 'Premium'],
                'layout' => 'Sathenapally Main',
                'layout_id' => 'main_layout'
            ],
            [
                'plot_id' => '12',
                'display_name' => '12',
                'block' => 'D',
                'coordinates' => [
                    ['x' => 4100, 'y' => 3233],
                    ['x' => 4829, 'y' => 3233],
                    ['x' => 4829, 'y' => 5554],
                    ['x' => 4100, 'y' => 5554]
                ],
                'price' => 5000000,
                'area' => 8500,
                'status' => 'available',
                'amenities' => ['Water', 'Electricity', 'Road Access', 'Commercial Use', 'Large Plot'],
                'layout' => 'Sathenapally Main',
                'layout_id' => 'main_layout'
            ]
        ];

        // Insert all plots
        foreach ($plotsData as $plotData) {
            Plot::create($plotData);
        }

        $this->command->info('Plots seeded successfully!');
    }
}
