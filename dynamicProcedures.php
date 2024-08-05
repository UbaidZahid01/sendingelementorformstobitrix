<?php
// procedure wise form testing
// Mapping of procedures to their IDs
$procedureToIdMap = [
    'AcneScarRemoval' => 434,
    'AntiAging' => 1636,
    'BikiniLHR' => 1738,
    'Botox' => 180,
    'ButtLiftButtFiller' => 188,
    'BreastEnhancement' => 422,
    'BariatricSurgery' => 1538,
    'BotoxUnderarms' => 1720,
    'CarbonLaserPeel' => 436,
    'CosmeticSurgery' => 1616,
    'CrystalPeel' => 1714,
    'ChemicalPeeling' => 1798,
    'PNM' => 768,
    'ScalingPolishing' => 192,
    'HairTransplantHTP' => 770,
    'HairPRP' => 780,
    'GCell' => 178,
    'HydraFacial' => 782,
    'LaserHairRemoval' => 182,
    'JawlineContouring' => 200,
    'RussianLips' => 184,
    'LipFillers' => 268,
    'FillersDerma' => 186,
    'RoyalSkinGlam' => 190,
    'Veneers' => 194,
    'PShot' => 198,
    'PenileFillersSizeMatters' => 416,
    'VaginalTightening' => 418,
    'IntimateAreasWhitening' => 420,
    'Gynecomastia' => 424,
    'Otoplasty' => 426,
    'LaserBeardRemoval' => 428,
    'DarkCircles' => 432,
    'PRPMeso' => 440,
    'FatMelting' => 494,
    'SkinWhitening' => 496,
    'LaserTattooRemoval' => 498,
    'Glowing' => 500,
    'HIFU' => 502,
    'SkinBooster' => 504,
    'WeightLoss' => 1540,
    'EnjoyInjection' => 506,
    'VampireHydrafacial' => 508,
    'IVDripsWeightLoss' => 510,
    'GastricBalloon' => 514,
    'RFBodyContouring' => 516,
    'Dental' => 1620,
    'PlasticSurgery' => 1628,
    'Gynecologist' => 1638,
    'HealthConsultation' => 1642,
    'DontOfferCommentPinned' => 1696,
    'Dermatology' => 1702,
    '3areasbotox' => 1722,
    'JawlineFiller' => 1732,
    'Fullbodylaser' => 1740,
    'PrivateAreaFiller' => 1750,
    'Peeling' => 1752,
    'LaserTightening' => 1754,
    'SkinLighting' => 1768,
    'LipLighting' => 1770,
    'GlowMeso' => 1780,
    'RFMicroNeedling' => 1786,
    'RF' => 1788,
    'DermaPen' => 1804,
    'OxygenoFacial' => 1806,
    'Threads' => 1852,
    'DimplePlasty' => 1854,
    'Physiotherapy' => 1856,
    'Labiaplasty' => 1858,
    'OShot' => 1860,
    'VaginalFiller' => 1862,
    'GFC' => 1888,
    'Mounjaro' => 1890,
    'Rhinoplasty' => 1892,
    'Contraceptive' => 1894,
    'SPAMNoResponseAtAll' => 1918,
    'TummyTuck' => 2140,
    'SMP' => 2152,
    'Hujama' => 2182,
    'FractionalLaser' => 2184,
    'PicoLaser' => 2186,
    'Hemorrhoids' => 2188,
    'Hernia' => 2190,
    'WeightlossInjection' => 2192,
    'Bloodtest' => 2194,
    'SlimmingMachine' => 2196,
    'Liposuction' => 2198,
    'UnderarmWhitening' => 2200,
    'Hijamah' => 2248,
    'AndrologyDepartment' => 2266,
    'HomeCareDepartment' => 2268,
    'Electrolysis' => 2284,
    'StomachBotox' => 2290,
    'PlasmaMagellan' => 2296,
    'Circumcision' => 2326,
    'Melasma' => 2332,
    'Bridge' => 2338,
    'Braces' => 2344,
    'UnderEyesFiller' => 2350,
    'ProcedureTesting'=>3508
];

// Function to parse and format the URL part
function formatUrlPart($url) {
    // Parse the URL to get the path
    $path = parse_url($url, PHP_URL_PATH);
    
    // Extract the procedure part from the path
    $pathSegments = explode('/', trim($path, '/'));
    $procedurePart = end($pathSegments);
    
    // Remove hyphens and capitalize the first letter of each word
    $formattedPart = str_replace(' ', '', ucwords(str_replace('-', ' ', $procedurePart)));
    
    return $formattedPart;
}

add_action('elementor_pro/forms/new_record', function($record, $handler) use ($procedureToIdMap) {
    // Make sure it's our form
    $form_name = $record->get_form_settings('form_name');

    // Replace MY_FORM_NAME with the name you gave your form
    if ('PPC_Dubai_Form' !== $form_name) {
        return;
    }

    $raw_fields = $record->get('fields');
    $fields = [];
    
    foreach ($raw_fields as $id => $field) {
        $fields[$id] = $field['value'];
    }
    
    $name = isset($fields["name"]) ? $fields["name"] : '';
    $number = isset($fields["phone"]) ? $fields["phone"] : '';
    $message = isset($fields["message"]) ? $fields["message"] : '';
    
    // Define the sendCurlRequest function inside the main function
    function sendCurlRequest($queryData, $action, $method = "crm.lead")
    {
        $endpoint = "https://enfield-royal.bitrix24.com/rest/1398/vyu0z55o0z2lqg6p/";
        $queryUrl = $endpoint . "/$method.$action/";
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $queryUrl,
            CURLOPT_POSTFIELDS => $queryData,
        ]);

        $result = curl_exec($curl);
        curl_close($curl);

        return json_decode($result, true); // Changed to true to return an associative array
    }

    // Checking if lead already exists
    $data['filter'] = [
        'PHONE' => $number,
        'SOURCE_ID' => "93"
    ];

    $data['select'] = ["ID", "UF_CRM_1677498079"];

    $queryData = http_build_query($data);

    $result_data = sendCurlRequest($queryData, "list", "crm.lead");

    // URL's
    $pagesubmitted = $_SERVER['HTTP_REFERER'];
    
    // Format the URL part
    $formattedPart = formatUrlPart($pagesubmitted);
       
    // Get the procedure ID from the mapping
	$procedureId = isset($procedureToIdMap[$formattedPart]) ? $procedureToIdMap[$formattedPart] : 768;
    
    $url_field = isset($result_data['result'][0]["UF_CRM_1677498079"]) ? $result_data['result'][0]["UF_CRM_1677498079"] : [];
    $url_link = array_merge($url_field, [$pagesubmitted]);
    $url_link = array_unique($url_link);
    
    $data['fields'] = [
        'TITLE' => $name,
        'NAME' => $name,
        'COMMENTS' => $message,
        'SOURCE_ID' => "93",
        "UF_CRM_1676875706" => [$procedureId], // Use the dynamic procedure ID
        "UF_CRM_1677498079" => $url_link
    ];
    $data['fields']['PHONE'] = [
        ["VALUE" => $number, "VALUE_TYPE" => 'WORK']
    ];

    $queryData = http_build_query($data);
    $result_data = sendCurlRequest($queryData, "add", "crm.lead");
}, 10, 2);

?>
