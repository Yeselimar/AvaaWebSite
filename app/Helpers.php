<?php

function getReceso()
{
	if(getRecesoActivo() and getRecesoVisible())
	{
		$fecha_actual = strtotime(date("d-m-Y 00:00:00"));
	    $fecha_inicio = strtotime(avaa\RecesoDecembrino::first()->fecha_inicio);
	    $fecha_fin = strtotime(avaa\RecesoDecembrino::first()->fecha_fin);

	    if($fecha_actual >= $fecha_inicio and $fecha_actual <= $fecha_fin)
	    {
	        return true;
	    }
	}
    return false;
}

function getRecesoActivo()
{
	if(avaa\RecesoDecembrino::count() == 0)
	{
		return false;
	}
	if( avaa\RecesoDecembrino::first()->activo==0)
	{
		return false;
	}
	else
	{
		return true;
	}
}

function getRecesoVisible()
{
	if(Auth::user()->esBecario())
	{
		return true;
	}
	return false;
}