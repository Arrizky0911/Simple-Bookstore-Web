
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<link href="static/style.css" rel="stylesheet">
<style>
        :root {
        --primary:white;
        --primary-hover: #CD7D7C;                
        --secondary: #EFD0F8;
        --secondary-hover: #B8A1D9;
        --aura: #9265CA80;
    }
    #styled-select select {
        width: 260px;
        padding: 5px;
        font-size: 15px;
        line-height: 1;
        border: 0;
        border-radius: 0;
        height: 34px;
        -webkit-appearance: none;
        font-family:helvetica-roman;
        -webkit-appearance: none;
        border: none;
        text-indent: 0.01px;
        text-overflow: '';
    }

    .footer-body {
        font-size: 16px;
        font-family: 'Inter', 
            sans-serif;
        display: flex;
        flex-direction: column;
        align-items: center;
        height: auto;
        min-width: 300px;
    }

    footer {
        bottom: 0px;
        background-color: var(
            --primary);
        width: 100%;
        min-width: 300px;
        bottom: 0px;
        display: flex;
        align-items: center;
        flex-direction: column;
    }

    .footer-wrapper {
        display: flex;
        flex-direction: column;
        max-width: 1024px;
        width: 100%;
        padding: 16px;
    }

    .footer-links {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        flex-grow: 4;
        gap: 48px 16px;
    }

    .footer-columns {
        display: flex;
        flex-wrap: wrap;
        padding: 24px 8px 36px 8px;
    }

    .footer-columns section, 
    .footer-columns .footer-logo {
        display: flex;
        flex-direction: column;
        flex: 1 0 160px;
        max-width: 160px;
    }

    .footer-columns ul {
        display: flex;
        gap: 12px;
        list-style-type: none;
        padding: 0;
        margin: 0;
        flex-direction: column;
        font-weight: 600;
    }

    .footer-columns ul a {
        color: var(--text-color);
        text-decoration: none;
    }

    .footer-columns ul a:hover{
        text-decoration: underline;
    }

    .footer-columns h3 {
        color: var(--text-heading-gray);
        margin-top: 0;
        font-size: 1rem;
    }

    .footer-logo {
        display: flex;
        gap: 16px;
        flex-grow: 1;
        max-width: 160px;
    }

    /* mobile */

    @media (min-width: 926px) {
        .footer-logo {
            margin-right: auto;
        }
    }

    @media (max-width: 800px) {
        .footer-top {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 16px 8px 32px 8px;
        }

    }
    .btn:focus {
        box-shadow: none;
    }   

</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> 
<script src="https://code.jquery.com/jquery-3.7.1.slim.js" integrity="sha256-UgvvN8vBkgO0luPSUl2s8TIlOSYRoGFAX4jlCIm9Adc=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() { 
                var $checkbox = $('#someCheckbox');
                var $collapseContainer = $('#collapseContainer');
                $checkbox.change(function() { 
                    $collapseContainer.collapse('toggle');
                }); 
                $collapseContainer.collapse($checkbox.prop('checked') ? "show" : "hide");

                $('#kurang').click(function () {
                    var $input = $('#jumlah');
                    var count = parseInt($input.val()) - 1;
                    count = count < 1 ? 1 : count;
                    $input.val(count);
                    $input.change();
                    return false;
                });
                $('#tambah').click(function () {
                    var $input = $('#jumlah');
                    $input.val(parseInt($input.val()) + 1);
                    $input.change();
                    return false;
                });

                $('#order-table').click(function () {
                    $(".show-account").css("display", "none");
                    $(".show-order").css("display", "block");
                    $(".show-addresses").css("display", "none");
                    return false;   
                });

                $('#your-account').click(function () {
                    $(".show-transaction").css("display", "none");
                    $(".show-history").css("display", "none");
                    $(".show-account").css("display", "block");
                    $(".show-addresses").css("display", "none");
                    return false;   
                });
                $('#history-order').click(function () {
                    $(".show-transaction").css("display", "none");
                    $(".show-history").css("display", "block");
                    $(".show-account").css("display", "none");
                    $(".show-addresses").css("display", "none");
                    return false;   
                });
                $('#current-transaction').click(function () {
                    $(".show-transaction").css("display", "block");
                    $(".show-history").css("display", "none");
                    $(".show-account").css("display", "none");
                    $(".show-addresses").css("display", "none");
                    return false;   
                });
                $('#your-address').click(function () {
                    $(".show-transaction").css("display", "none");
                    $(".show-history").css("display", "none");
                    $(".show-account").css("display", "none");
                    $(".show-addresses").css("display", "block");
                    return false;   
                });

                $('form').each(function(){
                    this.reset();
                });
            });
    
      
</script>
</head>