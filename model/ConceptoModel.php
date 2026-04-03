<?php

include_once __DIR__ . "/../config/mainModel.php";
header('Content-Type: text/html; charset=utf-8');
class ConceptoModel extends mainModel
{
    protected function obtenerConceptos()
    {
    	
        $db  = self::conectar();
        $sql = $db->prepare("SELECT * FROM conceptos");
        // sin obra social
        //$sql->bindParam(':empresa_id', $empresa_id, PDO::PARAM_INT);
        $sql->execute();
        
        return $sql->fetchAll(PDO::FETCH_ASSOC);
        /*
		return [
		    ['id' => 1, 'concepto' => 'Obra Social'],
		    ['id' => 2, 'concepto' => 'Seguro de Vida'],
		    ['id' => 3, 'concepto' => 'Fondo de Convenio'],
		    ['id' => 4, 'concepto' => 'Cuota Sindical'],
		    ['id' => 5, 'concepto' => 'Cont. Extraordinaria']
		];
        */
    }

    protected function obtenerDetalle($concepto)
    {
        // TODO: reemplazar con query real cuando la tabla detalles esté lista
        $db  = self::conectar();
        /*$sql = $db->prepare("
            SELECT *
                FROM concboleta c
                INNER JOIN ctabanco b ON c.cta_banco = b.cod_ctabanco
                INNER JOIN mercadopago mp ON mp.cod_ente = c.codEnte
                WHERE c.concepto = :concepto 
                AND c.tipDeposita = 1 
                AND c.TipSituacion = 'A'");*/
                
        $sql = $db->prepare("
            SELECT *
                FROM concboleta c
                INNER JOIN ctabanco b ON c.cta_banco = b.cod_ctabanco
                INNER JOIN mercadopago2 mp ON mp.codConcBoleta = c.codConcBoleta
                WHERE c.concepto = :concepto 
                AND c.tipDeposita = 1 
                AND c.TipSituacion = 'A'");

        $sql->bindParam(':concepto', $concepto, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
        /*
        $todos = [
            ['id' => 5,  'descripcion' => 'Convenio F.E.H.G.R.A.',        'concepto' => 3, 'tippago' => 'P', 'tippagoval' => 2.5],
            ['id' => 10, 'descripcion' => 'Convenio A.H.T. desde 2006/03', 'concepto' => 3, 'tippago' => 'P', 'tippagoval' => 2],
            ['id' => 50, 'descripcion' => 'Seguro de Vida',                'concepto' => 2, 'tippago' => 'P', 'tippagoval' => 3],
            ['id' => 51, 'descripcion' => 'Fondo de Convenio',             'concepto' => 3, 'tippago' => 'P', 'tippagoval' => 4],
            ['id' => 60, 'descripcion' => 'Cuota Sindical',                'concepto' => 4, 'tippago' => 'P', 'tippagoval' => 5],
            ['id' => 61, 'descripcion' => 'Cont. Extraordinaria',          'concepto' => 5, 'tippago' => 'P', 'tippagoval' => 6],
        ];

        return array_values(array_filter($todos, fn($d) => $d['concepto'] == $concepto));
        */
    }

    /**********************************************************
     * CUANDO EL USUARIO SELECCIONA FONDO DE CONVENIO
     * SE CARGAN SOLAMENTE LOS CONVENIOS EN EL COMBO DE DETALLE
     * ********************************************************/
    protected function obtenerDetalleFC($concepto){ 
        $db  = self::conectar();
        $sql = $db->prepare("SELECT * FROM concboleta where concepto = :concepto AND tipDeposita = 1 AND TipSituacion = 'A'");
        $sql->bindParam(':concepto', $concepto, PDO::PARAM_INT);
        $sql->execute();
        
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    /*****************************************************************
     * CUANDO EL USUARIO SELECCIONA CUOTA SINDICAL O CONTRIB SOLIDARIA
     * SE CARGAN SOLAMENTE LOS CONVENIOS EN EL COMBO DE DETALLE
     * **************************************************************/
    protected function obtenerDetalleCS($concepto){ 
        $db  = self::conectar();
        $sql = $db->prepare("SELECT id as cod-sec,  FROM seccionales");
        //$sql->bindParam(':concepto', $concepto, PDO::PARAM_INT);
        $sql->execute();
        
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function obtenerVencimiento($concepto_buscar, $periodo_buscar)
    {
        // $db  = self::conectar();
        // $sql = $db->prepare("SELECT * FROM conceptos");
        // //$sql->bindParam(':empresa_id', $empresa_id, PDO::PARAM_INT);
        // $sql->execute();

        //return $sql->fetchAll(PDO::FETCH_ASSOC);

		/*$concepto_buscar = 3;
		$periodo_buscar = '202601';*/

		$fecha_vencimiento = null;
        $vencimiento = [
					    ['concepto' => 3, 'periodo' => '202511', 'fec-venc' => '15/12/2025'],
					    ['concepto' => 3, 'periodo' => '202512', 'fec-venc' => '15/01/2026'],
					    ['concepto' => 3, 'periodo' => '202510', 'fec-venc' => '15/11/2025'],
					    ['concepto' => 3, 'periodo' => '202601', 'fec-venc' => '15/02/2026'],
					    ['concepto' => 3, 'periodo' => '202602', 'fec-venc' => '15/03/2026'],
					    ['concepto' => 3, 'periodo' => '202603', 'fec-venc' => '15/04/2026']
					];

		foreach ($vencimiento as $item) {
		    if ($item['concepto'] == $concepto_buscar && $item['periodo'] == $periodo_buscar) {
		        $fecha_vencimiento = $item['fec-venc'];
		        break; // Detener la búsqueda cuando encuentre
		    }
		}

		if ($fecha_vencimiento) {
		    return [ /*"Fecha de vencimiento encontrada: " .*/ $fecha_vencimiento];
		} else {
		    return [ /*"No se encontró vencimiento para el concepto $concepto_buscar y período $periodo_buscar"*/ ];
		}

		//return $fecha_vencimiento;
    }

    

    protected function obtenerRecargos($fecha_vencimiento, $fecha_pago = null)
    {
        $db  = self::conectar();
        $sql = $db->prepare("
            SELECT
                t_liquid                             AS `cod-tip-liq`,
                DATE_FORMAT(fecha_desde, '%d/%m/%Y') AS `fecha-desde`,
                DATE_FORMAT(fecha_hasta, '%d/%m/%Y') AS `fecha-hasta`,
                porcentaje
            FROM consolida
            WHERE t_liquid = 0
              AND fecha_hasta >= STR_TO_DATE(:fecha_venc, '%d/%m/%Y')
            ORDER BY fecha_desde ASC
        ");
        $sql->bindParam(':fecha_venc', $fecha_vencimiento, PDO::PARAM_STR);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }


    protected function obtenerEstablecimientoCompleto($cod_est, $id_empresa)
    {
    $db  = self::conectar();
    $sql = $db->prepare("
        SELECT
            est.cod_est,
            est.razon_social,
            est.id_sec,
            est.id_tipo,
            est.id_convenio,
            est.calle,
            est.numero,
            est.cod_pos,
            t.nombre  AS tipo_nombre,
            c.nombre  AS convenio_nombre,
            s.nombre  AS seccional_nombre,
            e.cuit    AS empresa_cuit,
            e.razon_social AS empresa_nombre
        FROM establecimientos est
        LEFT JOIN tipos       t ON est.id_tipo     = t.id
        LEFT JOIN convenios   c ON est.id_convenio = c.id
        LEFT JOIN seccionales s ON est.id_sec      = s.id
        LEFT JOIN empresas    e ON est.id_empresa  = e.id
        WHERE est.cod_est    = :cod_est
          AND est.id_empresa = :id_empresa
        LIMIT 1
    ");
    $sql->bindParam(':cod_est',    $cod_est,    PDO::PARAM_INT);
    $sql->bindParam(':id_empresa', $id_empresa, PDO::PARAM_INT);
    $sql->execute();
    return $sql->fetch(PDO::FETCH_ASSOC);
    }


}