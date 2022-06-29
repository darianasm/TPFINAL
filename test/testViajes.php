<?php

include_once '../datos/Empresa.php';
include_once '../datos/Responsable.php';
include_once '../datos/Viaje.php';
include_once '../datos/Pasajero.php';

$empresa = new Empresa();
$empresa->cargar(0,"Transporte","Neuquen");
//insertarBd($empresa);

$empresas = $empresa->listar();
$idEmpresa = $empresas[count($empresas)-1]->getIdempresa();
$empresa->setIdempresa($idEmpresa);


$responsable = new Responsable();
$responsable->cargar(0,12345,"Jose","Hernandez");
//insertarBd($responsable);

$responsables = $responsable->listar();
$idResponsable = $responsables[count($responsables)-1]->getRnumeroEmpleado();
$responsable->setRnumeroempleado($idResponsable);


$viaje1 = new Viaje();
$viaje1->cargar(0,"Bariloche",5,$empresa->getIdempresa(),$responsable,500,"primera clase","si");
//insertarBd($viaje1);

$viajes = $viaje1->listar();
$idViaje = $viajes[count($viajes)-1]->getIdviaje();
$viaje1->setIdviaje($idViaje);

$pasajero1 = new Pasajero();
$pasajero1->cargar(12345,"Maria","Muñoz",2996666666,$viaje1->getIdviaje());
//insertarBd($pasajero1);

$pasajero2 = new Pasajero();
$pasajero2->cargar(12346,"Petra","Sosa",2995666666,$viaje1->getIdviaje());
//insertarBd($pasajero2);

$viaje1->venderPasaje($pasajero1);
$viaje1->venderPasaje($pasajero2);

$pasajero3 = new Pasajero();
$pasajero3->cargar(12445,"Pedro","Muñoz",2996666766,$viaje1->getIdviaje());
//insertarBd($pasajero3);

$pasajero4 = new Pasajero();
$pasajero4->cargar(123469,"Petra","Hernandez",2995666786,$viaje1->getIdviaje());
//insertarBd($pasajero4);

$viaje1->venderPasaje($pasajero3);
$viaje1->venderPasaje($pasajero4);

$empresa->agregarViaje($viaje1);


opciones($empresa);


/**
 * función que da un menú para realizar lo que quiera el usuario con respecto a la empresa
 * @param object $empresa
 */
function opciones($empres){

     do{
        echo "\n-------Menú de opciones-------\n"
            ."1) Ingresar datos de una nueva empresa.\n"
            ."2) Modificar información de la empresa.\n"
            ."3) Eliminar información de la empresa.\n"
            ."4) Ver los datos de la empresa.\n"
            ."5) Salir.\n";
        
        echo "Ingrese su eleccion: ";
        $eleccion = trim(fgets(STDIN));
    
        //sale del programa o llama a los metodos dependiendo de la elección del usuario
        switch($eleccion){
            case 1:ingresarEmpresa($empres);break;
            case 2:modificarEmpresa($empres);break;
            case 3:eliminarEmpresa($empres);break;
            case 4:mostrarDatos($empres);break;
            case 5:echo "Programa finalizado";break;
            default:echo "Elección ingresada no valida, intente otra vez";break;
        }
    }while($eleccion!=5);
}

/**
 * función que le permite al usuario ingresar una empresa
 * @param object $empre
 */
function ingresarEmpresa($empre){

    echo "------Ingrese datos de la empresa------\n";
    echo "Ingrese nombre de la empresa: ";
    $nombre = trim(fgets(STDIN));
    echo "Ingrese dirección de la empresa: ";
    $direccion = trim(fgets(STDIN));

    $empre->cargar(0,$nombre,$direccion);
    insertarBd($empre);

    $empresas = $empre->listar();
    $idEmpresa = $empresas[count($empresas)-1]->getIdempresa();
    $empre->setIdempresa($idEmpresa);
    
    ingresarViajes($empre);
}

/**
 * función que le permite al usuario ingresar un viaje
 * @param object $empre
 */
function ingresarViajes($empresa){
    $i = 0;
    $seguir = "si";
    echo "\n------Ingrese viajes------\n";

    while(strcasecmp($seguir,"Si")==0){
        
        echo "------Ingrese datos del viaje------\n";
        echo "Ingrese destino del viaje: ";
        $destino = trim(fgets(STDIN));
        echo "Ingrese la cantidad máxima de pasajeros: ";
        $cantMax = trim(fgets(STDIN));
        echo "Ingrese importe del viaje: ";
        $importe = trim(fgets(STDIN));
        echo "Ingrese tipo de asiento del viaje: ";
        $tipoAsiento = trim(fgets(STDIN));
        echo "¿El viaje es de ida y vuelta?: ";
        $idaVuelta = trim(fgets(STDIN));
        
        $responsable = new Responsable();

        $viaje[$i] = new Viaje();

        $viaje[$i]->cargar(0,$destino,$cantMax, $empresa->getIdempresa(), $responsable, $importe, $tipoAsiento, $idaVuelta);
        
        ingresarResponsable($viaje[$i]);
        insertarBd($viaje[$i]);

        $viajes = $viaje[$i]->listar();
        $idViaje = $viajes[count($viajes)-1]->getIdviaje();

        $viaje[$i]->setIdviaje($idViaje);
            
        $empresa->agregarViaje($viaje[$i]); 
        ingresarPasajeros($viaje[$i]);
        
        $i++;

        echo "¿Desea ingresar otro viaje?: ";
        $seguir = trim(fgets(STDIN)); 
    }
    echo "\nLos datos de los viajes actualmente son: ".$empresa->stringViajes();
}

/**
 * función que le permite al usuario ingresar un responsable
 */
function ingresarResponsable($viaje){

    echo "---Ingrese datos del Responsable del Viaje---\n";
    echo "Ingrese número de licencia: ";
    $licencia = trim(fgets(STDIN));
    echo "Ingrese nombre: ";
    $nombre = trim(fgets(STDIN));
    echo "Ingrese apellido: ";
    $apellido = trim(fgets(STDIN));
            
    $viaje->getRefResponsable()->cargar(0,$licencia, $nombre, $apellido);
    insertarBd($viaje->getRefResponsable());

    $responsables = $viaje->getRefResponsable()->listar();
    $idResponsable = $responsables[count($responsables)-1]->getRnumeroempleado();

    $viaje->getRefResponsable()->setRnumeroempleado($idResponsable);
}

/**
 * Función que carga los datos de los pasajeros ingresados por el usuario
 * @param int $cantMaxima;
 * @param object $datosViaje;
 * @return array $pasajero;
 */
function ingresarPasajeros($datosViaje){
    $cantMaxima = $datosViaje->getVcantmaxpasajeros();
    $i = 0;
    $seguir = "si";
    echo "---Ingrese pasajeros---\n";
    
    //strcasemp() para comparar el 'si' sin importar las mayúsculas o minúsculas
    while(strcasecmp($seguir,"Si")==0 && $datosViaje->hayPasajesDisponibles()){
    
        echo "Ingrese nombre del pasajero: ";
        $nombre = trim(fgets(STDIN));
        echo "Ingrese apellido del pasajero: ";
        $apellido = trim(fgets(STDIN));
        echo "Ingrese número de documento: ";
        $nroDocu = trim(fgets(STDIN));
        echo "Ingrese número de Teléfono: ";
        $tlfno = trim(fgets(STDIN));
    
        $pasajero[$i] = new Pasajero();
        
        if($datosViaje->encontrarIndice($nroDocu) != null){
            echo "Este pasajero ya ha sido ingresado, ingrese otro\n";
        }else{

            $pasajero[$i]->cargar($nroDocu, $nombre, $apellido, $tlfno, $datosViaje->getIdviaje());
            $datosViaje->venderPasaje($pasajero[$i]); 
            insertarBd($pasajero[$i]);
        }
        $i++;
    
        if(!$datosViaje->hayPasajesDisponibles()){
            echo "Ya llegó a la cantidad límite de pasajeros\n";
        }else{
            echo "¿Desea seguir ingresando más pasajeros?\nIngrese 'Si' para continuar, 'No' para parar: ";    
            $seguir = trim(fgets(STDIN));
        }
    }

    echo "\n\nLos datos de los pasajeros actualmente son: ".$datosViaje->stringPasajeros();
}

/**
 * función que le permite al usuario modificar los datos de una empresa
 * @param object $empre
 */
function modificarEmpresa($empre){
    echo "Ingrese numero de id de la empresa que desea modificar: ";
    $id = trim(fgets(STDIN));
    $encontrada = $empre->Buscar($id); 

    if($encontrada == true){
     
        echo "\n------MODIFICAR: \n"
            ."1) Modificar nombre.\n"
            ."2) Modificar dirección.\n"
            ."3) Modificar viaje.\n"
            ."4) Eliminar un viaje.\n"
            ."5) Agregar un viaje.\n";

        echo "Ingrese su eleccion: ";
        $modificar = trim(fgets(STDIN));

        switch($modificar){

            case 1: echo "Ingrese nombre nuevo: ";
                    $nombre = trim(fgets(STDIN));
                    $empre->setEnombre($nombre);
                    break;

            case 2: echo "Ingrese dirección nueva: ";
                    $direccion = trim(fgets(STDIN));
                    $empre->setEdireccion($direccion);
                    break;
            case 3: modificarViaje($empre);break;
            case 4: eliminarViaje($empre);break;
            case 5: ingresarViajes($empre);break;
            default:echo "Elección ingresada no valida, intente otra vez";break;
        }

        modificarBd($empre);
        
    }else{    
        echo "\n¡Empresa no encontrada!\n";
    }    
}

/**
 * función que le permite al usuario modificar los datos de una empresa
 * @param object $viaje
 */
function modificarViaje($empresa){
    echo "Ingrese numero de id del viaje que desea modificar: ";
    $id = trim(fgets(STDIN));
    $indiceViaje = $empresa->encontrarIndice($id);
    $viaje = new Viaje();

   $encontrado = $viaje->Buscar($id); 

    $pasajeros = new Pasajero();
    if($encontrado == true){

        $viaje = $empresa->getColViajes()[$indiceViaje];
        do{
            echo "\n------Ingrese dato que desea MODIFICAR del viaje------\n"
                ."1) Destino.\n"
                ."2) Cantidad Maxima de pasajeros.\n"
                ."3) Responsable.\n"
                ."4) Importe.\n"
                ."5) Tipo de asiento.\n"
                ."6) Ida y vuelta del viaje.\n"
                ."7) Pasajeros.\n"
                ."8) Eliminar un pasajero.\n"
                ."9) Agregar un pasajero.\n"
                ."10) Volver.\n";
                
            echo "Ingrese su eleccion: ";
            $eleccion = trim(fgets(STDIN));
            
            //llama al metodo escogido por el usuario 
            switch($eleccion){
                case 1:echo "Ingrese destino nuevo del viaje: ";
                            $destino = trim(fgets(STDIN));
                            $viaje->setVdestino($destino);
                            break;
                case 2:echo "Ingrese cantidad maxima de pasajeros nueva del viaje: ";
                        $cantNueva = trim(fgets(STDIN));
                        if($cantNueva>count($viaje->getColPasajeros())){
                        $viaje->setVcantmaxpasajeros($cantNueva);break;
                        }else{
                        echo "La cantidad nueva es menor a la cantidad de pasajeros ya ingresados.\n"; 
                        }
                        break;
                case 3: modificarResponsable($viaje);break;
                case 4:echo "Ingrese nuevo importe del viaje: ";
                            $importe = trim(fgets(STDIN));
                            $viaje->setVimporte($importe);
                            break;
                case 5:echo "Ingrese nuevo tipo de asiento del viaje: ";
                            $tipoAsiento = trim(fgets(STDIN));
                            $viaje->setTipoAsiento($tipoAsiento);break;
                            break;
                case 6:echo "Ingrese si es de ida y vuelta o no: ";
                            $idavuelta = trim(fgets(STDIN));
                            $viaje->setIdayvuelta($idavuelta);break;
                            break;
                case 7: modificarDatosPasajero($viaje);break;
                case 8: eliminarPasajero($viaje);break;
                case 9: ingresarPasajeros($viaje);break;
                case 10: echo "Volviendo al menú principal...\n";break;
                default:echo "Elección inexistente, ingrese otra\n";break;
            }
        }while($eleccion!=10);
        
    modificarBd($viaje);
    }else{    
        echo "\n¡Viaje no encontrado!\n";
    }    
}

/**
 * función que modifica los datos del responsable del viaje
 */
function modificarResponsable($viaje){

    $responsable = $viaje->getRefResponsable();

    echo "Ingrese numero de empleado del responsable que desea modificar: ";
    $num = trim(fgets(STDIN));
    $encontrado = $responsable->Buscar($num); 

    if($encontrada == true){

    do{
        echo "------Ingrese que dato desea modificar del Responsable del viaje------\n"
            ."1) Número de Licencia.\n"
            ."2) Nombre.\n"
            ."3) Apellido.\n"
            ."4) Volver\n";
                
            echo "Ingrese su eleccion: ";
            $eleccion = trim(fgets(STDIN));
            
            switch($eleccion){
                case 1:echo "Ingrese número nuevo de Licencia: ";  
                                $numLicencia = trim(fgets(STDIN));
                                $responsable->setRnumerolicencia($numLicencia);
                                break;
                case 2:echo "Ingrese nombre nuevo: ";
                                $nombreNuevo = trim(fgets(STDIN));
                                $responsable->setRnombre($nombreNuevo);
                                break;
                case 3:echo "Ingrese apellido nuevo: ";
                                 $apellidoNuevo = trim(fgets(STDIN));
                                 $responsable->setRapellido($apellidoNuevo);
                                 break;
                case 4: "Volviendo al menú de modificar datos del viaje...\n";break;
                default:"Elección inexistente, ingrese otra";break;
                }
            }while($eleccion!=4);
            
        modificarBd($responsable);
        
        }else{    
            echo "\n¡Responsable no encontrado!\n";
        }    
    }


/**
 * función que modifica los datos de una pasajero en específico
 * @param int $indice;
 * @param object $datosViaje;
 */
function modificarDatosPasajero($datosViaje){
    echo "Ingrese documento del pasajero que desea modificar: ";
    $docu = trim(fgets(STDIN));
    $indicePasaj = $datosViaje->encontrarIndice($docu);
    
    $datosPasajero = new Pasajero();
    $encontrado = $datosPasajero->Buscar($docu); 

    if($encontrado == true){
        
        $datosPasajero = $datosViaje->getColPasajeros()[$indicePasaj];
        
        do{
            echo "------Ingrese que datos desea modificar del pasajero------\n"
                ."1) Nombre.\n"
                ."2) Apellido.\n"
                ."3) Documento.\n"
                ."4) Volver.\n";
            
            echo "Ingrese su eleccion: ";
            $eleccion = trim(fgets(STDIN));
        
            switch($eleccion){
                case 1:echo "Ingrese nombre nuevo del pasajero: ";
                            $nombreNuevo = trim(fgets(STDIN));
                            $datosPasajero->setPnombre($nombreNuevo);
                            
                            break;
                case 2:echo "Ingrese apellido nuevo del pasajero: ";  
                            $apellidoNuevo = trim(fgets(STDIN));
                            $datosPasajero->setPapellido($apellidoNuevo);
                            break;
                case 3:echo "Ingrese numero de documento nuevo del pasajero: ";
                            $docuNuevo = trim(fgets(STDIN));
                            $datosPasajero->setRdocumento($docuNuevo);
                            break;
                case 4: "Volviendo al menú de modificar la colección de pasajeros...\n";break;
                default:"Elección inexistente, ingrese otra";break;
            }
        }while($eleccion!=4);

        modificarBd($datosPasajero);
    }else{
        echo "\n!Pasajero no encontrado!\n";
    }
}

/**
 * función que le permite al usuario eliminar los datos de una empresa
 * @param object $empre
 */
function eliminarEmpresa($empre){
    echo "Ingrese numero de id de la empresa que desea eliminar: ";
    $id = trim(fgets(STDIN));
    $encontrada = $empre->Buscar($id); 

    if($encontrada ==true){
        
        eliminarBd($empre);

    }else{
        echo "\n¡Empresa no encontrada!\n";
    }
}

/**
 * función que le permite al usuario eliminar los datos de un Responsable de la Base de Datos
 * @param object $empre
 */
function eliminarResponsable(){
    $responsable = new Responsable();
    echo "Ingrese numero de empleado que desea eliminar: ";
    $num = trim(fgets(STDIN));
    $encontrado = $responsable->Buscar($num); 

    if($encontrado == true){
        
        eliminarBd($responsable);

    }else{
        echo "\n¡Responsable no encontrado!\n";
    }
}

/**
 * función que le permite al usuario eliminar los datos de un viaje
 * @param object $viaje
 */
function eliminarViaje($empresa){
    echo "Ingrese numero de id del viaje que desea eliminar: ";
    $id = trim(fgets(STDIN));
    $indiceViaje = $empresa->encontrarIndice($id);
    $viaje = new Viaje();

    if($indiceViaje != null){
        $viaje = $empresa->getColViajes()[$indiceViaje];
    }

    $encontrado = $viaje->Buscar($id); 

    if($encontrado ==true){
        eliminarBd($viaje);
        $empresa->eliminarViaje($id);
    }else{
        echo "\n¡Viaje no encontrado!\n";
    }
}

function eliminarPasajero($viaje){
    echo "Ingrese numero de documento del pasajero que desea eliminar: ";
    $docu = trim(fgets(STDIN));
    $indicePas = $viaje->encontrarIndice($docu);
    $pasajero = new Pasajero();
    
    if($indicePas != null){
    $pasajero = $viaje->getColPasajeros()[$indicePas];
    }

    $encontrado = $pasajero->Buscar($docu);

    if($encontrado == true){
        $viaje->eliminarPasajero($docu);
        eliminarBd($pasajero);
    }else{
        echo "¡Numero de documento de pasajero no encontrado!";
    }

}

/**
 * muestra los datos de el objeto empresa
 * @param object $datos;
 */
function mostrarDatos($empre){
    echo $empre;
}

function insertarBd($objeto){    
    $respuesta = $objeto->insertar();
	// Inserto el OBj Viaje en la base de datos
	if ($respuesta==true) {
			echo "\nOP INSERCION: los datos fueron ingresados en la BD\n";
			$colObjetos = $objeto->listar("");
            
			foreach ($colObjetos as $unObjeto){
				echo $unObjeto;
				echo "-------------------------------------------------------\n";
			}
	}else 
		echo $objeto->getmensajeoperacion();
}

function modificarBd($objeto){
    $respuesta = $objeto->modificar();
	        
    if ($respuesta==true) {
        //Busco todas las Empresas almacenadas en la BD y veo la modificacion realizada
        $colObjetos =$objeto->listar();
        echo " \nOP MODIFICACION: Los datos fueron actualizados correctamente\n";

        foreach ($colObjetos as $unObjeto){
            echo $unObjeto;
            echo "-------------------------------------------------------\n";
        }

        }else
            echo $objeto->getmensajeoperacion();
}

function eliminarBd($objeto){
    $respuesta = $objeto->eliminar();
        if ($respuesta==true) {
        
            //Busco todas las Empresas almacenadas en la BD y veo la modificacion realizada
            echo " \nOP ELIMINACION: los datos fueron eliminados correctamente\n";
            $colObjetos =$objeto->listar();

            foreach ($colObjetos as $unObjeto){
                echo $unObjeto;
                echo "-------------------------------------------------------\n";
            }

        }else
            echo $objeto->getmensajeoperacion();
}



?>

