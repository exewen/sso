$(function(){
    $('body').on('click', 'table[class*=table-sort] > tbody > tr > th:gt(0)' , function() {
        var orderby = $(this).attr('orderby');
        var sort = $(this).attr('sort');
        if (sort && orderby ){
            sort = sort == 'asc' ? 'asc' : 'desc';
            $('#orderby').val(orderby);
            $('#sort').val(sort);
            $("#search_form").submit();
        }
    });
    $("select[name='length']").change(function() {
        var length = $(this).val();
        if (length){
            $('#length').val(length);
            $("#search_form").submit();
        }
    });
});