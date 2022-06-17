<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="Generator" content="EditPlus®">
    <meta name="Author" content="">
    <meta name="Keywords" content="">
    <meta name="Description" content="">
    <title>API テスト</title>

    <style>
        #server-info { line-height: 2; }
        #server-info label { display: inline-block; width: 110px; padding-right: 15px; text-align: right; }
        #server-info input, #server-info select { padding: 5px; }
        #form_req { line-height: 1.8; }
        #form_req input { padding: 3px; }
        #form_req input:disabled { border: 0; color: black; }
        .param { width: 120px; }
        .value { width: 300px; }
        #btn_send { margin: 10px 0 0 130px; width:100px; }
    </style>

    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('js/utils.js') }}"></script>
</head>
<body>
<div id="server-info">
    <label for="server">Server: </label>
    <input type="text" id="server" value="https://yamaguchi.geoa.info/Yamaguchi/api/" size="60" /><br />
    <label for="protocol">Protocol: </label>
    <select id="protocol"><option value="">--- Select ---</option></select><br />
    <label for="authorization">Authorization: </label>
    Apikey <input type="text" id="authorization" value="hnIpYOlXIo33VcnCTBZ8s1NydTw3uxBHfXylykAIh1c=" size="60" /><br />
</div>
<hr />
<form id="form_req" action="" method="post">
    <input type="text" class="param" value="Parameter Name" disabled /> : <input type="text" class="value" value="Parameter Value" disabled /><br />
    <input type="text" id="param1" class="param" value="" /> : <input type="text" class="value" id="value1" value="" /><br />
    <input type="text" id="param2" class="param" value="" /> : <input type="text" class="value" id="value2" value="" /><br />
    <input type="text" id="param3" class="param" value="" /> : <input type="text" class="value" id="value3" value="" /><br />
    <input type="text" id="param4" class="param" value="" /> : <input type="text" class="value" id="value4" value="" /><br />
    <input type="text" id="param5" class="param" value="" /> : <input type="text" class="value" id="value5" value="" /><br />
    <input type="text" id="param6" class="param" value="" /> : <input type="text" class="value" id="value6" value="" /><br />
    <input type="text" id="param7" class="param" value="" /> : <input type="text" class="value" id="value7" value="" /><br />
    <input type="text" id="param8" class="param" value="" /> : <input type="text" class="value" id="value8" value="" /><br />
    <input type="text" id="param9" class="param" value="" /> : <input type="text" class="value" id="value9" value="" /><br />
    <input type="text" id="param10" class="param" value="" /> : <input type="text" class="value" id="value10" value="" /><br />
    <input type="button" id="btn_send" value="Submit" />
</form>
<hr />
<label for="result">Response: </label><br />
<textarea id="result" cols="80" rows="20"></textarea>

<script type="text/javascript">
    let csrfToken = '{{ csrf_token() }}';//alertObject({result:0,detail:[{a:1, b:1}, {a:2,b:2}]}, 0);
    let paramNames = {
        //'login' : {'USER_ID': 'jhs', 'USER_PWD': 'jhsjhs', 'TOOL_ID': '0', 'TOOL_VER': '1.0.0'},
        //'logout': {},
        'master': {},
        'collection/search' : {
            'genre[]':'', 'topic[]':'', 'facility':'', 'property':'', 'is_top_view':'1', 'keyword':'', 'point_id':'',
            'page':1, 'perPage':200
        },
        'collection/detail' : { 'collection_id':'1' },
        'point/search' : {
            'category':'', 'search_category[]':'', 'keyword':'',
            'page':1, 'perPage':200
        },
        'point/detail' : { 'point_id':'1' },
        'course/search' : {
            'point_id':''
        },
        'course/detail' : { 'course_id':'1' },
    };
    $(function() {
        //$("#server").val(location.protocol + "//" + location.host + $("#server").val());

        $("#btn_send").on("click", function() {
            if ($("#protocol").val() === "") {
                $("#result").val('API Protocolを選択してください。');
                return;
            }

            // Parameters
            //let url = $("#server").val() + $("#protocol").val() + "/";
            let url = '{{ route('api-test') }}';
            let data = {}, paramName, paramValue;
            for (let i = 1; i < 11; i ++) {
                paramName = $("#param"+i).val();
                paramValue = $("#value"+i).val();
                if (paramName.length == 0) {
                    continue;
                }

                if (paramName.substr(paramName.length-2) == "[]") { // array
                    paramName = paramName.substr(0, paramName.length-2);
                    paramValue = paramValue.length == 0 ? [] : paramValue.split(",");
                }
                data[paramName] = paramValue;
            }

            // Headers
            (csrfToken != undefined && csrfToken.length > 0) && $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            /*let apiKey = $("#authorization").val();
            if (apiKey.length > 0) {
                $.ajaxSetup({
                    headers: {
                        'Authorization': 'Apikey ' + apiKey
                    }
                });
            } else {
                $.ajaxSetup({
                    headers: {}
                });
            }*/

            // Send reqeust
            $("#result").val('処理中...');
            $.ajax({
                method: "post",
                dataType: "text",
                data: {
                    server: $("#server").val(),
                    apiKey: $("#authorization").val(),
                    protocol: $("#protocol").val(),
                    params: data
                },
                url: url,
                success: function(data, textStatus, jqXHR) {
                    let jsonData;
                    try {
                        jsonData = JSON.parse(data);
                    } catch (e) {
                        $("#result").val(data);
                        return;
                    }

                    $("#result").val(_showElements(jsonData, 0));
                    if (data.token) {
                        csrfToken = data.token;
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $("#result").val("エラー : " + textStatus + "\n" + errorThrown);
                }
            });
        });

        for (let protocolID in paramNames) {
            $("#protocol").append('<option value="' + protocolID + '">' + protocolID + '</option>');
        }
        $("#protocol").on("change", function() {
            let protocolID = $("#protocol").val();
            let i = 0;
            for (let paramName in paramNames[protocolID]) {
                i ++;
                $("#param" + i).val(paramName);
                $("#value" + i).val(paramNames[protocolID][paramName]);
            }
            for (i++; i < 11; i ++) {
                $("#param" + i).val("");
                $("#value" + i).val("");
            }
        });
    });
</script>
</body>
</html>
