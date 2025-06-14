<?php
// 获取传入的 channel ID，默认为 11
$id = $_GET['id'] ?? '11';
$url = "https://cfpwwwapi.kbs.co.kr/api/v1/landing/live/channel_code/$id";

$headers = [
    "accept: */*",
    "accept-language: zh-CN,zh;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6,zh-TW;q=0.5,zh-HK;q=0.4",
    "cache-control: no-cache",
    "origin: https://onair.kbs.co.kr",
    "pragma: no-cache",
    "priority: u=1, i",
    "referer: https://onair.kbs.co.kr/",
    "sec-ch-ua: \"Microsoft Edge\";v=\"137\", \"Chromium\";v=\"137\", \"Not/A)Brand\";v=\"24\"",
    "sec-ch-ua-mobile: ?0",
    "sec-ch-ua-platform: \"macOS\"",
    "sec-fetch-dest: empty",
    "sec-fetch-mode: cors",
    "sec-fetch-site: same-site",
    "user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0"
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

if (isset($data['channel_item'][0]['service_url'])) {
    $m3u8_url = $data['channel_item'][0]['service_url'];
    // 302 跳转
    header("Location: $m3u8_url", true, 302);
    exit;
} else {
    // 如果失败，返回 JSON 错误信息
    header("Content-Type: application/json");
    echo json_encode([
        "error" => true,
        "message" => "未找到 m3u8 地址"
    ], JSON_UNESCAPED_UNICODE);
}
?>
