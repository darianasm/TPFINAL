<?php

include_once "BaseDatos.php";

class Viaje{
    
    private $idviaje;
    private $vdestino;
    private $vcantmaxpasajeros;
    private $idempresa;
    private $refResponsable;
    private $rnumeroempleado;
    private $vimporte;
    private $tipoAsiento;/*primera clase o no, semicama o cama*/
    private $idayvuelta;/*si no*/
    private $colPasajeros;
    private $mensajeoperacion;

    // Metodo constructor de la clase Viaje
    public function  __construct(){   
        $this->idviaje = "";
        $this->vdestino = "";    
        $this->vcantmaxpasajeros = "";    
        $this->idempresa = "";
        $this->rnumeroempleado = "";
        $this->refResponsable = "";     
        $this->vimporte = "";  
        $this->tipoAsiento = "";   
        $this->idayvuelta = "";
    }

    public function cargar($id,$destino,$cantmaxpasajeros,$empresa, $responsable, $importe, $asiento, $idavuelta){		
        $this->setIdviaje($id);
        $this->setVdestino($destino);
        $this->setVcantmaxpasajeros($cantmaxpasajeros); 
        $this->setIdempresa($empresa);
        $this->setVimporte($importe);
        $this->setTipoAsiento($asiento);
        $this->setIdayvuelta($idavuelta);
        $this->setColPasajeros([]);
        $this->setRefResponsable($responsable);
    }


    public function getIdviaje(){
        return $this->idviaje;
    }
    public function setIdviaje($idviaje){
        $this->idviaje = $idviaje;
    }
    
    public function getVdestino(){
        return $this->vdestino;
    }
    public function setVdestino($vdestino){
        $this->vdestino = $vdestino;
    }
    
    public function getVcantmaxpasajeros(){
        return $this->vcantmaxpasajeros;
    }
    public function setVcantmaxpasajeros($vcantmaxpasajeros){
        $this->vcantmaxpasajeros = $vcantmaxpasajeros;
    }

    public function getIdempresa(){
        return $this->idempresa;
    }
    public function setIdempresa($idempresa){
        $this->idempresa = $idempresa;
    }

    public function getVimporte(){
        return $this->vimporte;
    }
    public function setVimporte($vimporte){
        $this->vimporte = $vimporte;
    }

    public function getTipoAsiento(){
        return $this->tipoasiento;
    }
    public function setTipoAsiento($tipoasiento){
        $this->tipoasiento = $tipoasiento;
    }

    public function getIdayvuelta(){
        return $this->idayvuelta;
    }
    public function setIdayvuelta($idayvuelta){
        $this->idayvuelta = $idayvuelta;
    }

    public function getMensajeoperacion(){
        return $this->mensajeoperacion;
    }
    public function setMensajeoperacion($mensajeoperacion){
        $this->mensajeoperacion = $mensajeoperacion;
    }

    public function getRefResponsable(){
        return $this->refResponsable;
    }
    public function setRefResponsable($refResponsable){
        $this->refResponsable = $refResponsable;
    }

    public function getColPasajeros(){
        return $this->colPasajeros;
    } 
    public function setColPasajeros($colPasajeros){
        $this->colPasajeros = $colPasajeros;
    }
    public function getRnumeroempleado(){
        return $this->rnumeroempleado;
    }
    public function setRnumeroempleado($rnumeroempleado){
        $this->rnumeroempleado = $rnumeroempleado;
    }
    
    /**
	 * Recupera los datos de un viaje por su id
	 * @param int $id
	 * @return true en caso de encontrar los datos, false en caso contrario 
	 */		
    public function Buscar($id){
		$base=new BaseDatos();
		$consultaViaje="select * from viaje where idviaje =".$id;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaViaje)){
				if($row2=$base->Registro()){
				    $this->setIdviaje($id);
					$this->setVdestino($row2['vdestino']);
					$this->setVcantmaxpasajeros($row2['vcantmaxpasajeros']);
                    $this->setIdempresa($row2['idempresa']);
                    $this->setRnumeroempleado($row2['rnumeroempleado']);
					$this->setVimporte($row2['vimporte']);
					$this->setTipoAsiento($row2['tipoAsiento']);
                    $this->setIdayvuelta($row2['idayvuelta']);
					$resp= true;
				}				
			
		 	}	else {
		 			$this->setmensajeoperacion($base->getError());
		 		
			}
		}   else {
		 		$this->setmensajeoperacion($base->getError());
		 	
		    }		
		return $resp;
	}
    

    public function listar($condicion=""){
	    $arregloViaje = null;
		$base= new BaseDatos();
		$consultaViajes ="select * from viaje ";
		if ($condicion!=""){
		    $consultaViajes=$consultaViajes.' where '.$condicion;
		}
		$consultaViajes.=" order by idviaje ";

		if($base->Iniciar()){
			if($base->Ejecutar($consultaViajes)){		
                
				$arregloViaje = array();
				while($row2=$base->Registro()){
                    
				    $id = $row2['idviaje'];
                    $dest = $row2['vdestino'];
					$maxpas = $row2['vcantmaxpasajeros'];
					$empre = $row2['idempresa'];
                    $numeple = $row2['rnumeroempleado'];
                    $import = $row2['vimporte'];
					$asien = $row2['tipoAsiento'];
					$idayvuel = $row2['idayvuelta'];
				
					$via = new Viaje();
					$via->cargar($id,$dest,$maxpas,$empre,$numeple,$import,$asien,$idayvuel);
					array_push($arregloViaje,$via);
	            }
				
			}	else {
		 		    $this->setmensajeoperacion($base->getError());		
			}
		}	else {
		 	    $this->setmensajeoperacion($base->getError());
		}	
		return $arregloViaje;
	}

    public function insertar(){
		$base=new BaseDatos();
		$resp= false;
		$consultaInsertar="INSERT INTO viaje(vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado, vimporte, tipoAsiento, idayvuelta) 
				VALUES ('".$this->getVdestino()."','".$this->getVcantmaxpasajeros()."','".$this->getIdempresa()."','".$this->getRefResponsable()->getRnumeroempleado()."','".$this->getVimporte()."','".$this->getTipoAsiento()."','".$this->getIdayvuelta()."')";
		
		if($base->Iniciar()){

			if($base->Ejecutar($consultaInsertar)){
                
			    $resp=  true;

			}	else {
					$this->setmensajeoperacion($base->getError());
		
			}

		} else {
				$this->setmensajeoperacion($base->getError());
			
		}
		return $resp;
	}
    public function modificar(){
	    $resp =false; 
	    $base=new BaseDatos();
		$consultaModifica="UPDATE viaje SET vdestino='".$this->getVdestino()."'
                                                , vcantmaxpasajeros='".$this->getVcantmaxpasajeros()."' 
                                                , idempresa='".$this->getIdempresa()."' 
                                                , rnumeroempleado='".$this->getRefResponsable()->getRnumeroempleado()."' 
                                                , vimporte='".$this->getVimporte()."' 
                                                , tipoasiento='".$this->getTipoAsiento()."' 
                                                , idayvuelta='".$this->getIdayvuelta()."' 
                                                WHERE idviaje =". $this->getIdviaje();
		if($base->Iniciar()){
			if($base->Ejecutar($consultaModifica)){
			    $resp=  true;
			}else{
				$this->setmensajeoperacion($base->getError());
				
			}
		}else{
				$this->setmensajeoperacion($base->getError());
			
		}
		return $resp;
	}
	
	public function eliminar(){
		$base=new BaseDatos();
		$resp=false;
		if($base->Iniciar()){
				$consultaBorra="DELETE FROM viaje WHERE idviaje=".$this->getIdviaje();
				if($base->Ejecutar($consultaBorra)){
				    $resp=  true;
				}else{
						$this->setmensajeoperacion($base->getError());	
				}
		}else{
				$this->setmensajeoperacion($base->getError());
			
		}
		return $resp; 
	}

    
/**
 * crea una nueva colecci??n omitiendo al pasajero seg??n su nro de deocumento
 * modifica la colecci??n
 * @param int $docu
 */
public function eliminarPasajero($docu){
    $pasajeros = $this->getColPasajeros();
    $pasajeroNuevo = [];
    $j = 0;
    for($i = 0; $i< count($pasajeros);$i++){
        if($pasajeros[$i]->getRdocumento() != $docu){
        $pasajeroNuevo[$j] = $pasajeros[$i];
        $j++;
        }
    }
    $this->setColPasajeros($pasajeroNuevo);
    }
    
    /**
     * crea una variable string con los datos de los pasajeros
     * @return String $string;
     */
    public function stringPasajeros(){
    $pasajeros = $this->getColPasajeros();
    $string = " ";
    $j = 1;
    for($i = 0; $i<(count($pasajeros));$i++){
    $string = $string.
              "\nPASAJERO ".($j++)
             .$pasajeros[$i];
             
    }
    return $string;
    }
    
    /**
     * agregar un arreglo aosciativo de un pasajero a una colecci??n de pasajeros
     * modifica el arreglo de pasajeros anterior
     * @param object $pasajero
     */
    public function agregarPasajero($pasajero){
    $pasajeros = $this->getColPasajeros();
    array_push($pasajeros,$pasajero);
    $this->setColPasajeros($pasajeros);
    }
    
    /**
     * encuentra el indice en donde se encuentra el pasajero en el array seg??n su nro de documento
     * si no encuentra el indice retorta null
     * @param int $docu
     * @return int $encontrado;
     */
    public function encontrarIndice($docu){
    $pasajeros = $this->getColPasajeros();
    
    $encontrado = null;
    $i=0;
    while($i<count($pasajeros) && $encontrado == null){
    if($pasajeros[$i]->getRdocumento() == $docu){
    $encontrado = $i;
    }
    $i++;
    }
    return $encontrado;
    }
    
    //Metodo para convertir en string
    public function __toString(){
        return "Codigo de viaje: ".$this->getIdviaje().
               "\nDestino del viaje: ".$this->getVdestino().
               "\nLa cantidad maxima de pasajeros es de: ".$this->getVcantmaxpasajeros().
               "\n\nLos datos los pasajeros son: \n".$this->stringPasajeros().
               "\nLos datos del responsable del viaje son: ".$this->getRefResponsable().
               "\nEl importe del viaje es: ".$this->getVimporte().
               "\nEl viaje es de ida y vuelta: ".$this->getIdayvuelta()."\n";
    }
    
    public function venderPasaje($pasajero){
    $idaVuel = $this->getIdayvuelta();
    $multiplicar = null;
    $importeBase = null;
    
    if($this->hayPasajesDisponibles()){
        $this->agregarPasajero($pasajero);
        $importeBase = $this->getVimporte();
        if($idaVuel == "si"){
            $importeBase += ($importeBase*50)/100;
        }

        $this->setVimporte($importeBase);
    
    }
    return $importeBase;
    }
    
    public function hayPasajesDisponibles(){
    $cantPasajeros = count($this->getColPasajeros());
    $maximaCant = $this->getVcantmaxpasajeros();
    $disponible = false;
    
    if($cantPasajeros < $maximaCant){
    $disponible = true;
    }
    return $disponible;
    }

	


    

    
    


    
}

?>