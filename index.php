<?php
$formatedCpf = null;
$formatArray = array(3, 6);

enum StatesEnum: int
{
    case STATES_1 = 1;
    case STATES_2 = 2;
    case STATES_3 = 3;
    case STATES_4 = 4;
    case STATES_5 = 5;
    case STATES_6 = 6;
    case STATES_7 = 7;
    case STATES_8 = 8;
    case STATES_9 = 9;
    case STATES_0 = 0;

    public function getStates(): string
    {
        return match ($this) {
            self::STATES_1 => 'DF, GO, MS, MT, TO',
            self::STATES_2 => 'AC, AM, AP, PA, RO, RR',
            self::STATES_3 => 'CE, MA, PI',
            self::STATES_4 => 'AL, PB, PE, RN',
            self::STATES_5 => 'BA, SE',
            self::STATES_6 => 'MG',
            self::STATES_7 => 'ES, RJ',
            self::STATES_8 => 'SP',
            self::STATES_9 => 'PR, SC',
            self::STATES_0 => 'RS',
        };
    }
}

if (isset($_POST['cpf'])) {
    $cpf = trim($_POST['cpf']);
    $cpf = preg_replace("/[^0-9]/", "", $cpf);
    if (strlen($cpf) == 11) {
        for ($i = 0; $i < 11; $i++) {
            if (in_array($i, $formatArray)) {
                $formatedCpf .= '.';
            } else if ($i == 9) {
                $formatedCpf .= '-';
            }
            $formatedCpf .= $cpf[$i];
        }
    } else {
        $formatedCpf = $_POST['cpf'];
    }

    $state = StatesEnum::from($cpf[8])->getStates();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>CPF Checker</title>
</head>

<body>
    <div class="container">
        <h1>CPF - Checker</h1>
        <form action="#" method="post">
            <label for="cpf">CPF</label>
            <input type="text" name="cpf" id="cpf" value="<?= $formatedCpf ?>">
            <input class="btn" type="submit" value="Check">
        </form>
        <?php
        if (isset($_POST['cpf'])) {
            if (cpfChecker($_POST['cpf'])) {
                echo "<p class='valid'>This is a valid CPF from </p>";
            } else {
                echo '<p class="invalid">This CPF is not valid</p>';
            };
        }
        ?>
    </div>
</body>

</html>

<?php

function cpfChecker()
{
    $not_allowed = array(
        "11111111111", "22222222222", "33333333333", "44444444444", "55555555555",
        "66666666666", "77777777777", "88888888888", "99999999999", "00000000000"
    );

    $cpf = trim($_POST['cpf']);
    $cpf = preg_replace("/[^0-9]/", "", $cpf);
    $cpf_len = strlen($cpf);

    if (!in_array($cpf, $not_allowed) && $cpf_len == 11) {
        if (firstCheck($cpf, intval($cpf['09']))) {
            if (secondCheck($cpf, intval($cpf['10']))) {
                return true;
                exit();
            }
        }
    }
}

function firstCheck($cpf, $verifier)
{
    $val = 10;
    $check = 0;
    for ($i = 0; $i < 9; $i++) {
        $check = $check + ($cpf[$i] * $val);
        --$val;
    }
    $check = $check % 11;
    $check = commmonCheck($check);
    return $check == $verifier ? true : false;
}

function secondCheck($cpf, $verifier)
{
    $val = 11;
    $check = 0;
    for ($i = 0; $i < 10; $i++) {
        $check = $check + ($cpf[$i] * $val);
        --$val;
    }
    $check = $check % 11;
    $check = commmonCheck($check);
    return $check == $verifier ? true : false;
}

function commmonCheck($check)
{
    if ($check < 2) {
        $check = 0;
    } else {
        $check = 11 - $check;
    }
    return $check;
}

var_dump($state)

?>
