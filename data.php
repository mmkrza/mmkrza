<?php
$file = "data.json";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $temp = $_POST["temp"] ?? null;
    $hum = $_POST["hum"] ?? null;

    if ($temp !== null && $hum !== null) {
        $data = [];

        // إذا كان الملف موجودًا، نقرأه
        if (file_exists($file)) {
            $data = json_decode(file_get_contents($file), true);
        }

        // نضيف قراءة جديدة مع الوقت الحالي
        $data[] = [
            "time" => date("H:i:s"),
            "temperature" => floatval($temp),
            "humidity" => floatval($hum)
        ];

        // نحفظ فقط آخر 50 قراءة لتقليل الحجم
        if (count($data) > 50) {
            $data = array_slice($data, -50);
        }

        file_put_contents($file, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        echo "تم الحفظ بنجاح";
        exit;
    }
}

// عند زيارة الصفحة مباشرة، نعرض الملف فقط
if (file_exists($file)) {
    header("Content-Type: application/json; charset=UTF-8");
    echo file_get_contents($file);
} else {
    echo json_encode([]);
}
?>
