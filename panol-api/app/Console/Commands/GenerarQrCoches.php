<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Coche;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GenerarQrCoches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coches:generar-qr';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera y guarda el QR para todos los coches existentes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $coches = Coche::all();
        foreach ($coches as $coche) {
            $url = url("/coches/{$coche->id}/historial");
            // Forzar uso de GD como backend
            $qr = base64_encode(QrCode::format('svg')->size(200)->generate($url));
            $coche->qr_code = $qr;
            $coche->save();
            $this->info("QR generado para coche ID {$coche->id}");
        }
        $this->info('Todos los QR fueron generados.');
    }
}
