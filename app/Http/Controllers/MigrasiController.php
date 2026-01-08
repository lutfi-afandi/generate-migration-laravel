<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MigrasiController extends Controller
{
    public function index()
    {
        // ambil semua database
        $databases = DB::select('SHOW DATABASES');

        return view('migrasi.index', compact('databases'));
    }

    public function tables(Request $request)
    {
        $db = $request->database;

        // switch database
        DB::statement("USE `$db`");

        $tables = DB::select('SHOW TABLES');

        $tableNames = collect($tables)->map(function ($row) use ($db) {
            return array_values((array)$row)[0];
        });

        return response()->json($tableNames);
    }

    public function generate(Request $request)
    {
        $db = $request->database;
        $table = $request->table;

        DB::statement("USE `$db`");

        $columns = DB::select("SHOW COLUMNS FROM `$table`");

        $script = "Schema::create('$table', function (Blueprint \$table) {\n";

        foreach ($columns as $col) {
            $script .= $this->convertColumn($col);
        }

        $script .= "});";

        return response()->json(['script' => $script]);
    }

    private function convertColumn($col)
    {
        if ($col->Key == 'PRI') {
            return "    \$table->id();\n";
        }

        $line = "    ";

        if (strpos($col->Type, 'varchar') !== false) {
            preg_match('/\d+/', $col->Type, $m);
            $length = isset($m[0]) ? $m[0] : 255;
            $line .= "\$table->string('{$col->Field}', $length)";
        } elseif (strpos($col->Type, 'int') !== false) {
            $line .= "\$table->integer('{$col->Field}')";
        } elseif (strpos($col->Type, 'text') !== false) {
            $line .= "\$table->text('{$col->Field}')";
        } elseif (strpos($col->Type, 'timestamp') !== false) {
            $line .= "\$table->timestamp('{$col->Field}')";
        } else {
            $line .= "\$table->string('{$col->Field}')";
        }

        if ($col->Null == 'YES') {
            $line .= "->nullable()";
        }

        return $line . ";\n";
    }


    public function all($database)
    {
        // switch database
        DB::statement("USE `$database`");

        $tables = DB::select('SHOW TABLES');

        $tableNames = collect($tables)->map(function ($row) {
            return array_values((array) $row)[0];
        });

        $output = [];

        foreach ($tableNames as $table) {
            $output[$table] = $this->buildMigration($table);
        }

        return view('migrasi.all', compact('output', 'database'));
    }



    private function buildMigration($table)
    {
        $columns = DB::select("SHOW COLUMNS FROM `$table`");

        $script = "Schema::create('$table', function (Blueprint \$table) {\n";

        $foreigns = [];

        foreach ($columns as $col) {

            if ($col->Field == 'id') {
                $script .= "    \$table->id();\n";
                continue;
            }

            if ($col->Field == 'created_at' || $col->Field == 'updated_at') {
                continue;
            }

            if (str_ends_with($col->Field, '_id')) {
                $related = str_replace('_id', '', $col->Field);

                $foreigns[] =
                    "    \$table->foreignId('{$col->Field}')\n" .
                    "          ->nullable()\n" .
                    "          ->constrained('{$related}s')\n" .
                    "          ->nullOnDelete();\n";

                continue;
            }


            $script .= $this->convertColumn($col);
        }

        $script .= "    \$table->timestamps();\n";

        foreach ($foreigns as $f) {
            $script .= $f;
        }

        $script .= "});";

        return $script;
    }
}
