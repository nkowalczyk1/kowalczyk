<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .content{
            border: solid 1px black;
            max-height: 30vh;
            overflow: auto;
        }
    </style>
    <script
    src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
    crossorigin="anonymous"></script>
</head>
<body>
    email
    <input type='text' class='email' onchange="$(this).attr('readonly', true); ">
    rss
    <input type='text' class='rss'>
    <br/>
    Dane z rss:
    <div  class='content'>

    </div>
    Linki:
    <div  class='linki'>

    </div>
    <br/>
    <button type="button" onclick='save()'>save</button>
    <button type="button" onclick='send()'>send</button>
</body>

<script>
    $( document ).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        getLinks();
    });
    function save(){
        $.ajax({
            method: "POST",
            url: "/saveLink",
            data: { 'link': $(".rss").val()}
        })
        .done(function( msg ) {
            alert( "Link zapisany");
            getLinks();
        });
    }
    function send(){
        $.ajax({
            method: "POST",
            url: "/sendData",
            data: { 'email': $(".email").val()}
        })
        .done(function( msg ) {
            $('.content').empty();
            $('.content').append(msg);
        });
    }
    function getLinks(){
        $.ajax({
            method: "GET",
            url: "/loadLinks",
        })
        .done(function( msg ) {
            var linki = '';
            msg = JSON.parse(msg);
            msg.forEach(rozloz);
            function rozloz(item){
                linki += '<p>' + item.link + '</p> <button onclick="deleteLink('+ item.id +')">X</button>';
                linki += '</br>';
            }
            $('.linki').empty();
            $('.linki').append(linki);
        });
    }
    function deleteLink(id){
        $.ajax({
            method: "POST",
            url: "/deleteLink",
            data: { 'id': id}
        })
        .done(function( msg ) {
            alert('Link usunieto');
            getLinks();
        });
    }
</script>