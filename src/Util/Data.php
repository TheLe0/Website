<?php

    namespace src\Util {
        class Data {
            protected $dia;
            protected $mes;
            protected $ano;

            public function __construct($dia_data = null, $mes = null, $ano = null)
            {
                switch (true) {
                    case empty($dia_data):
                        $this->setFromBR(date('d/m/Y'));
                        break;
                    case ( preg_match('/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$/', $dia_data)) :
                        $this->setFromBR($dia_data);
                        break;
                    case ( preg_match('/^[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}$/', $dia_data)) :
                        $this->setFromUS($dia_data);
                        break;
                    default :
                    $dia = (int) $dia_data;
                    $dia = ($dia <= 0)?1:$dia;
                    $this->setData($dia, $mes, $ano);
                    break;
                }
        
            }

            private function setData($dia, $mes, $ano)
            {
                $mk = mktime(0, 0, 0, $mes, $dia, $ano);
                $dia = date('d', $mk);
                $this->setDia($dia);

                $mes = date('m', $mk);
                $this->setMes($mes);

                $ano = date('Y', $mk);
                $this->setAno($ano);
            }

            public static function getValidDate($data)
            {
                if(!preg_match('/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/', $data)){
                        return date('d/m/Y');
                }
                $vData = explode('/', $data);
                if(!checkdate($vData[1], $vData[0], $vData[2])){
                        return date('d/m/Y');
                }
                return $data;
            }

            public static function dt2br($dateus)
            {
                if (!empty($dateus)){
                    if (preg_match("/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})/", $dateus, $regs)){
                        return ($regs[3]."/".$regs[2]."/".$regs[1]);
                    } else {
                        return (substr($dateus, 6, 2)."/".substr($dateus, 4, 2)."/".substr($dateus, 0, 4));
                    }
                } else {
                    return ("");
                }
            }

            public static function dt2us($datebr)
            {
                if (!empty($datebr)){
                    if (preg_match("/([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})/", $datebr, $regs)){
                        return ($regs[3]."-".$regs[2]."-".$regs[1]);
                    } else {
                        return (substr($datebr, 4, 4)."-".substr($datebr, 2, 2)."-".substr($datebr, 0, 2));
                    }
                } else {
                    return ("");
                }
            }

            protected function setFromBR($data)
            {
                $arr = explode('/', $data);
                $this->setData($arr[0], $arr[1], $arr[2]);
            }

            protected function setFromUS($data)
            {
                $arr = explode('-', $data);
                $this->setData($arr[2], $arr[1], $arr[0]);
            }

            public function setDia($dia)
            {
                $this->dia = $dia;
            }

            public function getDia()
            {
                return $this->dia;
            }

            public function setMes($mes)
            {
                $this->mes = $mes;
            }

            public function getMes()
            {
                return $this->mes;
            }

            public function setAno($ano)
            {
                $this->ano = $ano;
            }

            public function getAno()
            {
                return $this->ano;
            }

            public static function date()
            {
                return date('Y-m-d');
            }

            public static function dateBr()
            {
                return date('d/m/Y');
            }

            public static function time()
            {
                return date('H:i:s');
            }

            public static function datetime()
            {
                return date('Y-m-d H:i:s');
            }

            public static function datetimeBr()
            {
                return date('d/m/Y H:i:s');
            }

            public function toBR()
            {
                $dia = str_pad($this->getDia(), 2, '0', STR_PAD_LEFT);
                $mes = str_pad($this->getMes(), 2, '0', STR_PAD_LEFT);

                return $dia.'/'.$mes.'/'.$this->getAno();
            }

            public function toUS()
            {
                $dia = str_pad($this->getDia(), 2, '0', STR_PAD_LEFT);
                $mes = str_pad($this->getMes(), 2, '0', STR_PAD_LEFT);

                return $this->getAno().'-'.$mes.'-'.$dia;
            }

            public function toMkTime()
            {
                return mktime(0, 0, 0, $this->getMes(), $this->getDia(), $this->getAno());
            }

            public function equals(Data $data)
            {
                if(
                    $this->getDia() == $data->getDia()
                    &&
                    $this->getMes() == $data->getMes()
                    &&
                    $this->getAno() == $data->getAno()
                    )
                {
                    return true;
                } else {
                    return false;
                }
            }

            public function isHoje()
            {
                $flag = false;

                $d = (int) $this->getDia();
                $m = (int) $this->getMes();
                $a = (int) $this->getAno();

                if ( ($d == date('d') ) && ($m == date('m') ) && ($a == date('Y') )) {
                    $flag = true;
                }

                return $flag;
            }

            public function proximoDiaUtil($data)
            {
                $timestamp = strtotime('+1 day', strtotime($data));
                // 1 (para Segunda) at� 7 (para Domingo)
                $dia_semana = date('N', $timestamp);
                // SE FOR SABADO 6 OU DOMINGO 7
                if ($dia_semana >= 6) {
                    $timestamp_final = strtotime((8 - $dia_semana).' days', $timestamp);
                } else {
                    $timestamp_final = $timestamp;
                }

                return date('Y-m-d', $timestamp_final);
            }
        }
    }
