{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KYC Pasien</title>
    <script type="text/javascript">
        const url = "{{ $validation_web['data']['url'] }}";

        function loadFormPopup() {
            let params = `scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no,width=0,height=0,left=100,top=100`;
            window.open(url, "KYC", params);
        }

        function loadFormNewTab() {
            window.open(url, "_blank");
        }
    </script>
</head>
<body>
    <button onclick="loadFormPopup()">KYC Pasien Popup</button>
    <button onclick="loadFormNewTab()">KYC Pasien New Tab</button>
</body>
</html> --}}
@extends('layouts.app')
@section('content')
    <script type="text/javascript">
        const url = "{{ $validation_web['data']['url'] }}";

        function loadFormPopup() {
            let params = `scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no,width=0,height=0,left=100,top=100`;
            window.open(url, "KYC", params);
        }

        function loadFormNewTab() {
            window.open(url, "_blank");
        }
    </script>

    <div style="display: flex; justify-content: center; align-items: center; height: 100vh; flex-direction: column;">
        <button onclick="loadFormPopup()" style="background-color: #52C997; color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 4px 2px; cursor: pointer; border: none; border-radius: 4px;">KYC Pasien Popup</button>
        {{-- <button onclick="loadFormNewTab()" style="background-color: #52C997; color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 4px 2px; cursor: pointer; border: none; border-radius: 4px;">KYC Pasien New Tab</button> --}}
    </div>
@endsection
