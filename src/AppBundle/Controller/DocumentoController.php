<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BackendBundle\Entity\Documento;

class DocumentoController extends Controller
{
    public function indexAction()
    {
        return $this->render('BackendBundle:Default:index.html.twig');
    }
    
    
    public function newAction(Request $request) {
		$helpers = $this->get("app.helpers");
        //validamos la autorizacion
        $hash = $request->get("authorization",null);
        $checkAuth = $helpers->checkAuth($hash);
        
		if($checkAuth == true){
            $json = $request->get("json", null);
            
            if($json != null){//recuperar parametros e ir validando            
                $params = json_decode($json);//recibimos la cadena json que validamos en el if
                $nombre=(isset($params->$nombre))?$params->nombre:NULL;//si existe le asigno el valor para nombre sino le pongo nulo
                $numero=(isset($params->$numero))?$params->numero:NULL;   
               
                if($nombre!=null && $numero!=null){
                    $descripcion=(isset($params->$descripcion))?$params->descripcion:NULL;
                    $fechaRegistro=new \DateTime("now");
                    
                    $em = $this->getDoctrine()->getManager();
                    
                    //consulta
                    $documento_aux=getRepository("BackendBundle:Documento")->findOneBy("numero",$numero);
                   
                    if($documento_aux==NULL){
                    
                    $documento=new Documento();
                    $documento->setNumero($numero);
                    $documento->setNombre($nombre);
                    $documento->setFechaRegistro($fechaRegistro);
                    $documento->setDescripcion($descripcion);
                    
                    
                    $em->persist($documento);
                    $em->flush();
                    
                    $data = $helpers->msgData("Dpcumento creado!!","success",200, $documento);
                    
                    }else{
                        $data = $helpers->msgData("Error de Duplicacion");
                    }
                    
                }
                else{
                   $data = $helpers->msgData("Falta el numero y el nombre");
                }
                
                
                
            }
            else{
                $data = $helpers->msgData("No se enviaron los datos");
            }
		} else {
			$data = $helpers->msgData("No autorizado a esta sección");
		}
		return $helpers->json($data);
	}
        
        
         /*public function listAction(Request $request)
    {
        
        $documentos=$em->getRepository("BackendBundle:Documento")->findBy(array("activo"=>true));
        if(count($documentos)>0){
            $data=$helpers->msgData("Listado correctamente","success",200,$personas);
        }
             
        else{
            $data = $helpers->msgData("No hay datos");
        }
        
        return $helpers->json($data);
        //return $helpers->json($request);
    }
    
     public function listAction(Request $request)
    {
        $helpers = $this->get("app.helpers");
        
        $hash = $request->get("authorization",null);
        $checkAuth = $helpers->checkAuth($hash);
        
        if($checkAuth == true){
            $em = $this->getDoctrine()->getManager();
            $documentos = $em->getRepository("BackendBundle:Documento")
                                     ->findBy(array("activo" => TRUE));
            $data = $helpers->msgData("Exito","success",200,$documentos);
        }
        else{
            $data = $helpers->msgData("No autorizado a esta sección");
        }
        
        return $helpers->json($data);
        //return $helpers->json($request);
    }*/
    
    public function editAction(Request $request, $id=null)
    {
        $helpers = $this->get("app.helpers");
        
        $hash = $request->get("authorization",null);
        $checkAuth = $helpers->checkAuth($hash);
        
        if($checkAuth == true){
            $json = $request->get("json", null);

          
            if($json != null){
                $params = json_decode($json);
                
                $nombre=(isset($params->$nombre))?$params->nombre:NULL;//si existe le asigno el valor para nombre sino le pongo nulo
                $numero=(isset($params->$numero))?$params->numero:NULL;
                              
                    
                    $em = $this->getDoctrine()->getManager();
                    
                    $documento = $em->getRepository("BackendBundle:Documento")
                                          ->findOneBy(array("id" => $id));
                    
                    // Validamos que el numero sea unico
                    $numero = (isset($params->numero))? $params->numero:null;                    
                    if($numero != null){
                        $isset_numero = $em->getRepository("BackendBundle:Documento")
                                        ->findOneBy(array("numero" => $numero));
                        if($isset_numero != null){
                            $data = $helpers->msgData("Numero duplicado");
                            return $helpers->json($data);
                        }
                    }
                    
                    $fechaRegistro = new \DateTime("now");
                    
                    $documento = new Documento();
                    $nombre->setNombre($nombre);
                    $numero->setNumero($numero);
                    
                    //
                    if($nombre!=null && $numero!=null){
                    
                    $nombre = (isset($params->nombre))? $params->nombre:null;
                    $numero=(isset($params->$numero))?$params->numero:NULL;
                    $documento = new Documento();
                    $documento->setNombre($nombre);
                    $documento->setNumero($numero);
                    
                    $em->persist($documento);
                    $em->flush();
                    
                    $data = $helpers->msgData("Persona Natural editada!!","success",200,$documento);
                    
                }
                else{
                    $data = $helpers->msgData("Faltan datos");
                }
            }
            else{
                $data = $helpers->msgData("Datos no enviados");
            }
        }
        else{
            $data = $helpers->msgData("No autorizado a esta sección");
        }
        
        return $helpers->json($data);
    }
    
    
    
    
}

