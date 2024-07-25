<?php

use Illuminate\Support\Str;


if (! function_exists('getRandomString')) {
    function getRandomString($length = 10)
    {
        return Str::random($length);
    }
}

if (! function_exists('case_human')) {
    function case_human($value)
    {
        return Str::ucfirst(Str::lower(Str::replace('_', ' ', $value)));
    }
}

if (! function_exists('human_case')) {
    function human_case($string)
    {
        return Str::title(_(Str::snake(Str::studly($string), ' ')));
    }
}

if (!function_exists('gerarTokenAleatorio')) {
    function gerarTokenAleatorio($comprimento = 32)
    {
        $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $tamanhoCaracteres = strlen($caracteres);
        $token = '';

        for ($i = 0; $i < $comprimento; $i++) {
            $indice = random_int(0, $tamanhoCaracteres - 1);
            $token .= $caracteres[$indice];
        }

        return md5($token);
    }
}

if (!function_exists('readerFileJson')) {
    function readerFileJson($file): array
    {
        return json_decode(file_get_contents($file), true);
    }
}

if (!function_exists('validaFloat')) {
    function validaFloat($dado): float
    {
        $find = array("'", "\'", '%');
        $arr = str_replace($find, '', $dado);
        $arrFt = str_replace(',', '.', $arr);
        return floatval($arrFt);
    }
}

if (!function_exists('filtraCampo')) {
    function filtraCampo($dado): string
    {
        return addslashes(trim(strip_tags($dado)));
    }
}

if (!function_exists('geraSlug')) {
    function geraSlug($dado): string
    {
        return Str::slug(addslashes(trim(strip_tags($dado))), '_');
    }
}


if (!function_exists('replace')) {
    function replace(): array
    {
        $replace = array("\r\n", "\n", "\r", "\f");
        return $replace;
    }
}

if (!function_exists('validador')) {
    function validador(): array
    {
        $validador = array(null, false, '', ' ', []);
        return $validador;
    }
}

if (!function_exists('geraProtocolo')) {
    function geraProtocolo(): string
    {
        return uniqid() . date('z') . date('W') . date('t') . date('w') . date('d') . date('m') . date('Y') . date('H') . date('i') . date('s'). rand(0, 9);
    }
}

if (!function_exists('extract_emails_from')) {
    function extract_emails_from($string): array
    {
        $pattern = '/([a-z0-9_\.\-])+\@(([a-z0-9\-])+\.)+([a-z0-9]{2,4})+/i';
        preg_match_all($pattern, $string,$matches);
        return isset($matches[0]) ? $matches[0] : array();
    }
}

if (!function_exists('arr_fil')) {
    function arr_fil($array): array
    {
        return array_unique($array);
    }
}

if (!function_exists('array_assc')) {
    function array_assc($array): array
    {
        asort($array);
        $new_array = [];
        foreach ($array as $chave => $valor) {
            $new_array[] = $valor;
        }
        return $new_array;
    }
}

if (!function_exists('array_desc')) {
    function array_desc($array): array
    {
        rsort($array);
        $new_array = [];
        foreach ($array as $chave => $valor) {
            $new_array[] = $valor;
        }
        return $new_array;
    }
}

if (!function_exists('convertFloat')) {
    function convertFloat($dado): float
    {
        // Remover os pontos de milhar
        $valorString = str_replace('.', '', $dado);

        // Substituir a vírgula decimal por um ponto
        $valorString = str_replace(',', '.', $valorString);

        // Converter para float
        $valorFloat = (float) $valorString;

        return floatval($valorFloat);
    }
}

if (!function_exists('geraSlug')) {
    function geraSlug($dado): string
    {
        return Str::slug(addslashes(trim(strip_tags($dado))), '_');
    }
}

if (!function_exists('extrai_html')) {
    function extrai_html($dados): string
    {
        return trim(strip_tags(addslashes(html_entity_decode($dados))));
    }
}

if (!function_exists('filter_array')) {
    function filter_array($dados): array
    {
        return array_diff(array_filter(array_unique($dados)), array('', "", false, null));
    }
}

if (!function_exists('busca_site')) {
    function busca_site($dados): array
    {
        $site = explode("\n", file_get_contents($dados));
        $array = [];
        foreach ($site as $key => $value) {
            $linha = trim(strip_tags(addslashes(html_entity_decode(str_replace(["\r\n", "\n", "\r", "\f", "-->", "<!--"], '', $value)))));
            if (!in_array($linha, [null, false, '', ' ', []])) {
                $array[] = $linha;
            }
        }
        return array_filter($array);
    }
}

if (!function_exists('request_site')) {
    function request_site($dados): array
    {
        return json_decode(file_get_contents($dados), true);
    }
}

if (!function_exists('limpa_post')) {
    function limpa_post($dados): array
    {
        $arDados = [];
        foreach ($dados as $key => $values) {
            $arDados[$key] = trim(addslashes(strip_tags(utf8_decode($values))));
        }
        return $arDados;
    }
}

if (!function_exists('retiraAcentos')) {
    function retiraAcentos($string)
    {
        $acentos  =  'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
        $sem_acentos  =  'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
        $string = strtr($string, utf8_decode($acentos), $sem_acentos);
        return utf8_decode(strtoupper(trim($string)));
    }
}

if (!function_exists('soLetras')) {
    function soLetras($string)
    {
        return retiraAcentos(preg_replace("/[^a-zA-Z ]/", "", $string));
    }
}

if (!function_exists('validarSenha')) {
    /*  Função para validar senha
    *   A senha deve ter pelo menos 8 caracteres
     * A senha deve conter pelo menos uma letra maiúscula
     * A senha deve conter pelo menos uma letra minúscula
     * A senha deve conter pelo menos um número
     * A senha deve conter pelo menos um caractere especial
     * @param string $senha
     * @return array
     */
    function validarSenha(string $senha): array
    {
        $erros = [];

        // Verificar se a senha tem pelo menos 8 caracteres
        if (strlen($senha) < 8) {
            $erros[] = "A senha deve ter pelo menos 8 caracteres.";
        }

        // Verificar se contém pelo menos uma letra maiúscula
        if (!preg_match('/[A-Z]/', $senha)) {
            $erros[] = "A senha deve conter pelo menos uma letra maiúscula.";
        }

        // Verificar se contém pelo menos uma letra minúscula
        if (!preg_match('/[a-z]/', $senha)) {
            $erros[] = "A senha deve conter pelo menos uma letra minúscula.";
        }

        // Verificar se contém pelo menos um número
        if (!preg_match('/[0-9]/', $senha)) {
            $erros[] = "A senha deve conter pelo menos um número.";
        }

        // Verificar se contém pelo menos um caractere especial
        if (!preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $senha)) {
            $erros[] = "A senha deve conter pelo menos um caractere especial.";
        }

        // Se houver erros, retornar detalhes dos erros
        if (!empty($erros)) {
            return [
                'valid' => false,
                'errors' => $erros,
            ];
        }

        // Se passou por todas as verificações, a senha é válida
        return ['valid' => true];
    }
}

if (!function_exists('validarEmail')) {
    function validarEmail($email): bool
    {
        $e = strtolower(addslashes(trim($email)));

        if (filter_var($e, FILTER_VALIDATE_EMAIL) === false) {
            return false;
        }

        if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $e)) {
            return false;
        }

        return true;
    }
}
