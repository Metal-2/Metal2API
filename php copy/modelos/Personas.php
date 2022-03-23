<?php 
 class Personas{
    
    const SELECT_BASICO = <<<EOD
            SELECT  `personas`.* FROM `intranet`.`personas` 
EOD;
    
    public static function buscarPorTipoYNumeroIdentificacion($tipoIdentificacionID,$personaIDENTIFICACION){
        $sql=self::SELECT_BASICO . "WHERE tipoIdentificacionID=? AND personaIDENTIFICACION=?";
        return Conexion::selectUnaFila($sql,[$tipoIdentificacionID,$personaIDENTIFICACION]);
    }


    public static function guardarBasico($tipoPersonaID,$tipoIdentificacionID,$personaIDENTIFICACION,$personaPRIMERNOMBRE=NULL,$personaSEGUNDONOMBRE=NULL,$personaNOMBRES=NULL,$personaPRIMERAPELLIDO=NULL,$personaSEGUINDOAPELLIDO=NULL,$personaAPELLIDOS=NULL,$personaRAZONSOCIAL=NULL,$personaCORREO=NULL){
        $sql=<<<EOD
            INSERT INTO `personas` (
                tipoPersonaID, 
                tipoIdentificacionID, 
                personaIDENTIFICACION,
                personaPRIMERNOMBRE,
                personaSEGUNDONOMBRE,
                personaNOMBRES,
                personaPRIMERAPELLIDO,
                personaSEGUINDOAPELLIDO,
                personaAPELLIDOS,
                personaRAZONSOCIAL,
                personaCORREO
                )
            VALUES (?,?,?,?,?,?,?,?,?,?,?)
EOD;

        return Conexion::insertFila($sql,[$tipoPersonaID,$tipoIdentificacionID,$personaIDENTIFICACION,$personaPRIMERNOMBRE,$personaSEGUNDONOMBRE,$personaNOMBRES,$personaPRIMERAPELLIDO,$personaSEGUINDOAPELLIDO,$personaAPELLIDOS,$personaRAZONSOCIAL,$personaCORREO]);
    }

    public static function guardar($tipoPersonaID,$tipoIdentificacionID,$personaIDENTIFICACION,$personaPRIMERNOMBRE=NULL,$personaSEGUNDONOMBRE=NULL,$personaNOMBRES=NULL,$personaPRIMERAPELLIDO=NULL,$personaSEGUINDOAPELLIDO=NULL,$personaAPELLIDOS=NULL,$personaRAZONSOCIAL=NULL,$personaCORREO=NULL,$personaCELULAR=NULL,$nivelEscolarID=NULL,$usuarioUSR=NULL,$personaFCHNACIMIENTO=NULL,$personaDIRECCION=NULL,$fondoPensionID=NULL,$epsID=NULL,$arlID=NULL,$personaNUMEROHIJOS=NULL,$personaMUNICIPIONACIMIENTO=NULL,$personaGENERO=NULL,$personaESTADOCIVIL=NULL,$personaRUT=NULL,$personaMUNICIPIOEXPEDICION=NULL,$personaEDAD=NULL){
        $sql=<<<EOD
            INSERT INTO  `intranet`.`personas` (
                tipoPersonaID, 
                tipoIdentificacionID, 
                personaIDENTIFICACION,
                personaPRIMERNOMBRE,
                personaSEGUNDONOMBRE,
                personaNOMBRES,
                personaPRIMERAPELLIDO,
                personaSEGUINDOAPELLIDO,
                personaAPELLIDOS,
                personaRAZONSOCIAL,
                personaCORREO,
                personaCELULAR,
                nivelEscolarID,
                personaUSRCREO,
                personaFCHNACIMIENTO,
                personaDIRECCION,
                fondoPensionID,
                epsID,
                arlID,
          		personaNUMEROHIJOS,
                personaMUNICIPIONACIMIENTO,
                personaGENERO,
                personaESTADOCIVIL,
                personaRUT,
                personaMUNICIPIOEXPEDICION,
                personaEDAD
                )
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
EOD;

        return Conexion::insertFila($sql,[$tipoPersonaID,$tipoIdentificacionID,$personaIDENTIFICACION,$personaPRIMERNOMBRE,$personaSEGUNDONOMBRE,$personaNOMBRES,$personaPRIMERAPELLIDO,$personaSEGUINDOAPELLIDO,$personaAPELLIDOS,$personaRAZONSOCIAL,$personaCORREO,$personaCELULAR,$nivelEscolarID,$usuarioUSR,$personaFCHNACIMIENTO,$personaDIRECCION,$fondoPensionID,$epsID,$arlID,$personaNUMEROHIJOS,$personaMUNICIPIONACIMIENTO,$personaGENERO,$personaESTADOCIVIL,$personaRUT,$personaMUNICIPIOEXPEDICION, $personaEDAD]);
    }

    public static function actualizar($personaID,$tipoPersonaID,$tipoIdentificacionID,$personaIDENTIFICACION,$personaPRIMERNOMBRE=NULL,$personaSEGUNDONOMBRE=NULL,$personaNOMBRES=NULL,$personaPRIMERAPELLIDO=NULL,$personaSEGUINDOAPELLIDO=NULL,$personaAPELLIDOS=NULL,$personaRAZONSOCIAL=NULL,$personaCORREO=NULL,$personaCELULAR=NULL,$nivelEscolarID=NULL,$usuarioUSR=NULL,$personaFCHNACIMIENTO=NULL,$personaDIRECCION=NULL,$fondoPensionID=NULL,$epsID=NULL,$arlID=NULL,$personaNUMEROHIJOS=NULL,$personaMUNICIPIONACIMIENTO=NULL,$personaGENERO=NULL,$personaESTADOCIVIL=NULL,$personaRUT=NULL,$personaMUNICIPIOEXPEDICION=NULL, $personaEDAD =NULL){
        $sql=<<<EOD
            UPDATE `intranet`.`personas` SET
                tipoPersonaID =?, 
                tipoIdentificacionID=?, 
                personaIDENTIFICACION=?,
                personaPRIMERNOMBRE=?,
                personaSEGUNDONOMBRE=?,
                personaNOMBRES=?,
                personaPRIMERAPELLIDO=?,
                personaSEGUINDOAPELLIDO=?,
                personaAPELLIDOS=?,
                personaRAZONSOCIAL=?,
                personaCORREO=?,
                personaCELULAR=?,
                nivelEscolarID=?,
                personaUSRMODIFICO=?,
                personaFCHNACIMIENTO=?,
                personaDIRECCION=?,
                fondoPensionID=?,
                epsID=?,
                arlID=?,
                personaNUMEROHIJOS=?,
                personaMUNICIPIONACIMIENTO=?,
                personaGENERO=?,
                personaESTADOCIVIL=?,
                personaRUT=?,
                personaMUNICIPIOEXPEDICION=?,
                personaEDAD=?
                  
                WHERE personaID = ?
EOD;
        return Conexion::actualizarFila($sql,[$tipoPersonaID,$tipoIdentificacionID,$personaIDENTIFICACION,$personaPRIMERNOMBRE,$personaSEGUNDONOMBRE,$personaNOMBRES,$personaPRIMERAPELLIDO,$personaSEGUINDOAPELLIDO,$personaAPELLIDOS,$personaRAZONSOCIAL,$personaCORREO,$personaCELULAR,$nivelEscolarID,$usuarioUSR,$personaFCHNACIMIENTO,$personaDIRECCION,$fondoPensionID,$epsID,$arlID,$personaNUMEROHIJOS,$personaMUNICIPIONACIMIENTO,$personaGENERO,$personaESTADOCIVIL,$personaRUT,$personaMUNICIPIOEXPEDICION, $personaEDAD, $personaID]);
    }

    public static function gestionarPersona($tipoPersonaID,$tipoIdentificacionID,$personaIDENTIFICACION,$personaPRIMERNOMBRE=NULL,$personaSEGUNDONOMBRE=NULL,$personaNOMBRES=NULL,$personaPRIMERAPELLIDO=NULL,$personaSEGUINDOAPELLIDO=NULL,$personaAPELLIDOS=NULL,$personaRAZONSOCIAL=NULL,$personaCORREO=NULL,$personaCELULAR=NULL,$nivelEscolarID=NULL,$usuarioUSR=NULL,$personaFCHNACIMIENTO=NULL,$personaDIRECCION=NULL,$fondoPensionID=NULL,$epsID=NULL,$arlID=NULL,$personaNUMEROHIJOS=NULL ,$personaMUNICIPIONACIMIENTO=NULL,$personaGENERO=NULL,$personaESTADOCIVIL=NULL,$personaRUT=NULL,$personaMUNICIPIOEXPEDICION=NULL, $personaEDAD = NULL){
        $busqueda=self::buscarPorTipoYNumeroIdentificacion($tipoIdentificacionID,$personaIDENTIFICACION);
        if($busqueda){
            $personaPRIMERNOMBRE=is_null($personaPRIMERNOMBRE)?$busqueda->personaPRIMERNOMBRE:$personaPRIMERNOMBRE;
            $personaSEGUNDONOMBRE=is_null($personaSEGUNDONOMBRE)?$busqueda->personaSEGUNDONOMBRE:$personaSEGUNDONOMBRE;
            $personaNOMBRES=is_null($personaPRIMERNOMBRE)?NULL:$personaPRIMERNOMBRE." ".$personaSEGUNDONOMBRE ;
            $personaPRIMERAPELLIDO=is_null($personaPRIMERAPELLIDO)?$busqueda->personaPRIMERAPELLIDO:$personaPRIMERAPELLIDO;
            $personaSEGUINDOAPELLIDO=is_null($personaSEGUINDOAPELLIDO)?$busqueda->personaSEGUINDOAPELLIDO:$personaSEGUINDOAPELLIDO;
            $personaAPELLIDOS=is_null($personaPRIMERAPELLIDO)?NULL:$personaPRIMERAPELLIDO." ".$personaSEGUINDOAPELLIDO ;
            $personaRAZONSOCIAL=is_null($personaRAZONSOCIAL)?$personaNOMBRES." ".$personaAPELLIDOS:$personaRAZONSOCIAL;
            $personaCORREO=is_null($personaCORREO)?$busqueda->personaCORREO:$personaCORREO;
            $personaCELULAR=is_null($personaCELULAR)?$busqueda->personaCELULAR:$personaCELULAR;
           
            $personaFCHNACIMIENTO=is_null($personaFCHNACIMIENTO)?$busqueda->personaFCHNACIMIENTO:$personaFCHNACIMIENTO;
            $personaEDAD=is_null($personaEDAD)?$busqueda->personaEDAD:$personaEDAD;
            
            $personaDIRECCION=is_null($personaDIRECCION)?$busqueda->personaDIRECCION:$personaDIRECCION;
            $fondoPensionID=is_null($fondoPensionID)?$busqueda->fondoPensionID:$fondoPensionID;
            $epsID=is_null($epsID)?$busqueda->epsID:$epsID;
            $arlID=is_null($arlID)?$busqueda->arlID:$arlID;
          	$personaNUMEROHIJOS=is_null($personaNUMEROHIJOS)?$busqueda->personaNUMEROHIJOS:$personaNUMEROHIJOS;
          	$personaMUNICIPIONACIMIENTO=is_null($personaMUNICIPIONACIMIENTO)?$busqueda->personaMUNICIPIONACIMIENTO:$personaMUNICIPIONACIMIENTO;
            $personaMUNICIPIOEXPEDICION=is_null($personaMUNICIPIOEXPEDICION)?$busqueda->personaMUNICIPIOEXPEDICION:$personaMUNICIPIOEXPEDICION;
            $personaGENERO=is_null($personaGENERO)?$busqueda->personaGENERO:$personaGENERO;
            $personaESTADOCIVIL=is_null($personaESTADOCIVIL)?$busqueda->personaESTADOCIVIL:$personaESTADOCIVIL;
            $personaRUT=is_null($personaRUT)?$busqueda->personaRUT:$personaRUT;

            $nivelEscolarID=is_null($nivelEscolarID)?$busqueda->nivelEscolarID:$nivelEscolarID;
            self::actualizar($busqueda->personaID,$tipoPersonaID,$tipoIdentificacionID,$personaIDENTIFICACION,$personaPRIMERNOMBRE,$personaSEGUNDONOMBRE,$personaNOMBRES,$personaPRIMERAPELLIDO,$personaSEGUINDOAPELLIDO,$personaAPELLIDOS,$personaRAZONSOCIAL,$personaCORREO,$personaCELULAR,$nivelEscolarID,$usuarioUSR,$personaFCHNACIMIENTO,$personaDIRECCION,$fondoPensionID,$epsID,$arlID,$personaNUMEROHIJOS,$personaMUNICIPIONACIMIENTO,$personaGENERO,$personaESTADOCIVIL,$personaRUT,$personaMUNICIPIOEXPEDICION, $personaEDAD);
            return $busqueda->personaID;
        }else{
            $personaNOMBRES=is_null($personaPRIMERNOMBRE)?NULL:$personaPRIMERNOMBRE." ".$personaSEGUNDONOMBRE ;
            $personaAPELLIDOS=is_null($personaPRIMERAPELLIDO)?NULL:$personaPRIMERAPELLIDO." ".$personaSEGUINDOAPELLIDO ;
            $personaRAZONSOCIAL=is_null($personaRAZONSOCIAL)?$personaNOMBRES." ".$personaAPELLIDOS:$personaRAZONSOCIAL;
            return self::guardar($tipoPersonaID,$tipoIdentificacionID,$personaIDENTIFICACION,$personaPRIMERNOMBRE,$personaSEGUNDONOMBRE,$personaNOMBRES,$personaPRIMERAPELLIDO,$personaSEGUINDOAPELLIDO,$personaAPELLIDOS,$personaRAZONSOCIAL,$personaCORREO,$personaCELULAR,$nivelEscolarID,$usuarioUSR,$personaFCHNACIMIENTO,$personaDIRECCION,$fondoPensionID,$epsID,$arlID,$personaNUMEROHIJOS,$personaMUNICIPIONACIMIENTO,$personaGENERO,$personaESTADOCIVIL,$personaRUT,$personaMUNICIPIOEXPEDICION, $personaEDAD);
        }

    }
 }