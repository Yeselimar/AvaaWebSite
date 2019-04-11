<?php

namespace avaa\Console\Commands;

use Illuminate\Console\Command;

use avaa\Alerta;
use avaa\Becario;
use avaa\Nomina;
use avaa\Costo;
use avaa\User;
use avaa\BecarioNomina;
use DateTime;
use Illuminate\Support\Facades\DB;

class CronJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CronJob:cronjob';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'User Name Change Successfully';

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
     * @return mixed
     */
    public function handle()
    {
        $hoy = new DateTime();
        //inactivos
        Becario::where('fecha_inactivo', '!=', null)->where('fecha_inactivo','<=',$hoy)->update(array('status' => 'inactivo'));
        Becario::where('fecha_desincorporado', '!=', null)->where('fecha_desincorporado','<=',$hoy)->update(array('status' => 'desincorporado'));

        $this->info('Success');
    }
}
