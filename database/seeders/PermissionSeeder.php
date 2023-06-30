<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $features = Feature::all();

        foreach ($features as $index => $feature) {
            $name = strtolower($feature->name);
            $featureDescription = str_replace('-', ' ', strtolower($feature->name));

            Permission::insert([
                ["name" => "lihat {$name}",  "description" => "lihat {$featureDescription}",  "guard_name" => "web", "feature_id" => $feature->id],
                ["name" => "tambah {$name}", "description" => "tambah {$featureDescription}", "guard_name" => "web", "feature_id" => $feature->id],
                ["name" => "ubah {$name}",   "description" => "ubah {$featureDescription}",   "guard_name" => "web", "feature_id" => $feature->id],
                ["name" => "hapus {$name}",  "description" => "hapus {$featureDescription}",  "guard_name" => "web", "feature_id" => $feature->id],
            ]);
        }

        $listAdds = Config("library.permission_added");

        foreach ($listAdds as $index => $add) {
            Permission::insert([
                "name" => $add["name"],  "description" => "",  "guard_name" => "web", "feature_id" => $add["featurer_id"],
            ]);
        }
    }
}
