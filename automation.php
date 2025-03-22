<?php
/*Sending Forms To Bitrix*/

add_action('elementor_pro/forms/new_record', function($record, $handler) {
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
    
    $name1 = isset($fields["name"]) ? $fields["name"] : '';
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

    $data['select'] = ["ID", "ID_of_the_field"];

    $queryData = http_build_query($data);

    $result_data = sendCurlRequest($queryData, "list", "crm.lead");

    // URL's
    $pagesubmitted = $_SERVER['HTTP_REFERER'];
    $url_field = isset($result_data['result'][0]["ID_of_the_field"]) ? $result_data['result'][0]["ID_of_the_field"] : [];
    $url_link = array_merge($url_field, [$pagesubmitted]);
    $url_link = array_unique($url_link);

    $data['fields'] = [
        'TITLE' => $name1,
        'NAME' => $name1,
        'COMMENTS' => $message,
        'SOURCE_ID' => "93",
        "UF_CRM_1676875706" => [768],
        "ID_of_the_field" => $url_link
    ];
    $data['fields']['PHONE'] = [
        ["VALUE" => $number, "VALUE_TYPE" => 'WORK']
    ];

    $queryData = http_build_query($data);
    $result_data = sendCurlRequest($queryData, "add", "crm.lead");
}, 10, 2);

?>
