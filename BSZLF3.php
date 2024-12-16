<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Style Rechner</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #1A2D42, #2E4156);
            color: #D4D8DD;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-rendering: optimizeLegibility;
        }
        .container {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 20px 30px;
            width: 500px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.37);
            text-align: center;
        }
        h1 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #F5EBFA;
        }
        label {
            color: #E7DBEF;
            font-size: 1rem;
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: none;
            font-size: 1rem;
        }
        input, select {
            background: rgba(255, 255, 255, 0.2);
            color: #D4D8DD;
        }
        input:focus, select:focus {
            outline: none;
            border: 1px solid #AAB7B7;
        }
        button {
            background: #A56ABD;
            color: #F5EBFA;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
        }
        button:hover {
            background: #49225B;
        }
        .theme-buttons {
            display: flex;
            justify-content: space-around;
            margin-bottom: 15px;
        }
        .theme-button {
            flex: 1;
            margin: 0 5px;
            padding: 8px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        .theme1 { background: #1A2D42; color: #D4D8DD; }
        .theme2 { background: #3E2522; color: #FFE0B2; }
        .theme3 { background: #423736; color: #F4E2D1; }
        .theme4 { background: #49225B; color: #E7DBEF; }
        .theme-button:hover {
            opacity: 0.8;
        }
        .results {
            margin-top: 15px;
            background: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }
        .result-item {
            margin-bottom: 10px;
            color: #F5EBFA;
        }
        .error {
            color: red;
            font-weight: bold;
        }
    </style>
    <script>
        function setTheme(theme) {
            const themes = {
                theme1: { bg: 'linear-gradient(to right, #1A2D42, #2E4156)', text: '#D4D8DD' },
                theme2: { bg: 'linear-gradient(to right, #3E2522, #8C6E63)', text: '#FFE0B2' },
                theme3: { bg: 'linear-gradient(to right, #423736, #987185)', text: '#F4E2D1' },
                theme4: { bg: 'linear-gradient(to right, #49225B, #6E3482)', text: '#E7DBEF' },
            };
            document.body.style.background = themes[theme].bg;
            document.body.style.color = themes[theme].text;
        }
    </script>
</head>
<body>
<div class="container">
    <h1>Binär, Hexadezimal und Dezimal Rechner</h1>
    <div class="theme-buttons">
        <button class="theme-button theme1" onclick="setTheme('theme1')">Theme 1</button>
        <button class="theme-button theme2" onclick="setTheme('theme2')">Theme 2</button>
        <button class="theme-button theme3" onclick="setTheme('theme3')">Theme 3</button>
        <button class="theme-button theme4" onclick="setTheme('theme4')">Theme 4</button>
    </div>
    <form method="post">
        <label for="input">Zahl eingeben:</label>
        <input type="text" id="input" name="input" required>
        <label for="input-format">Eingabeformat:</label>
        <select id="input-format" name="input-format">
            <option value="binary">Binär</option>
            <option value="decimal">Dezimal</option>
            <option value="hexadecimal">Hexadezimal</option>
        </select>
        <label for="output-format">Ausgabeformat:</label>
        <select id="output-format" name="output-format">
            <option value="binary">Binär</option>
            <option value="decimal">Dezimal</option>
            <option value="hexadecimal">Hexadezimal</option>
        </select>
        <button type="submit">Konvertieren</button>
    </form>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = $_POST['input'];
        $inputFormat = $_POST['input-format'];
        $outputFormat = $_POST['output-format'];

        try {
            if (empty($input)) {
                throw new Exception("Bitte geben Sie eine Zahl ein.");
            }

            $decimal = null;
            $steps = "";

            // Eingabe -> Dezimal (mit Rechenweg)
            switch ($inputFormat) {
                case 'binary':
                    if (!preg_match('/^[01]+$/', $input)) {
                        throw new Exception("Ungültiges Binärformat.");
                    }
                    $decimal = 0;
                    $length = strlen($input);
                    for ($i = 0; $i < $length; $i++) {
                        $bit = $input[$length - $i - 1];
                        $power = pow(2, $i);
                        $value = $bit * $power;
                        $steps .= "Stelle $bit × 2^$i = $value<br>";
                        $decimal += $value;
                    }
                    $steps .= "Gesamtsumme: $decimal<br>";
                    break;
                case 'hexadecimal':
                    if (!preg_match('/^[0-9a-fA-F]+$/', $input)) {
                        throw new Exception("Ungültiges Hexadezimalformat.");
                    }
                    $decimal = 0;
                    $length = strlen($input);
                    $hexMap = array_merge(range(0, 9), array_combine(range('a', 'f'), range(10, 15)));
                    for ($i = 0; $i < $length; $i++) {
                        $char = strtolower($input[$length - $i - 1]);
                        $value = $hexMap[$char];
                        $power = pow(16, $i);
                        $result = $value * $power;
                        $steps .= "Stelle $char ($value) × 16^$i = $result<br>";
                        $decimal += $result;
                    }
                    $steps .= "Gesamtsumme: $decimal<br>";
                    break;
                case 'decimal':
                    if (!is_numeric($input)) {
                        throw new Exception("Ungültiges Dezimalformat.");
                    }
                    $decimal = (int)$input;
                    $steps .= "Eingabe ist bereits Dezimal: $decimal<br>";
                    break;
                default:
                    throw new Exception("Ungültiges Eingabeformat.");
            }

            // Dezimal -> Zielausgabeformat (mit Rechenweg)
            switch ($outputFormat) {
                case 'binary':
                    $output = "";
                    $current = $decimal;
                    while ($current > 0) {
                        $remainder = $current % 2;
                        $steps .= "$current ÷ 2 = " . intdiv($current, 2) . " Rest $remainder<br>";
                        $output = $remainder . $output;
                        $current = intdiv($current, 2);
                    }
                    $steps .= "Binärer Wert: $output<br>";
                    break;
                case 'hexadecimal':
                    $output = "";
                    $hexMap = array_merge(range(0, 9), array_combine(range(10, 15), range('a', 'f')));
                    $current = $decimal;
                    while ($current > 0) {
                        $remainder = $current % 16;
                        $char = $hexMap[$remainder];
                        $steps .= "$current ÷ 16 = " . intdiv($current, 16) . " Rest $char ($remainder)<br>";
                        $output = $char . $output;
                        $current = intdiv($current, 16);
                    }
                    $steps .= "Hexadezimaler Wert: $output<br>";
                    break;
                case 'decimal':
                default:
                    $output = $decimal;
                    $steps .= "Dezimal bleibt unverändert: $output<br>";
                    break;
            }

            echo "<div class='results'>";
            echo "<div class='result-item'><strong>Eingabeformat:</strong> $inputFormat<br><strong>Zahl:</strong> $input</div>";
            echo "<div class='result-item'><strong>Ausgabeformat:</strong> $outputFormat<br><strong>Ergebnis:</strong> $output</div>";
            echo "<div class='result-item'><strong>Rechenweg:</strong><br>$steps</div>";
            echo "</div>";
        } catch (Exception $e) {
            echo "<div class='error'>{$e->getMessage()}</div>";
        }
    }
    ?>

</div>
</body>
</html>
