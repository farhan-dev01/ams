jQuery(document).ready(function(){
    // Filter Popup Script
    jQuery('a.addEmployee').click(function(){
        $('.filterSection').css('top','90px');
    });
    jQuery('.applyBtn').click(function(){
        $('.filterSection').css('top','-450px');
    });

    // Push Menu Script
    new mlPushMenu( document.getElementById( 'mp-menu' ), document.getElementById( 'trigger' ) );
    // jQuery(document).ready(function($){
    //     jQuery('#trigger').toggle(function(){
    //         //jQuery('.logo').hide();
    //     });
    //     $('[data-toggle="datepicker"]').datepicker();
    // });

    // Toggle Attendance
    jQuery('.popOver').hide();
    jQuery('.employeeListDesc li a').click(function(){
        jQuery('.popOver').toggle();
        //alert('hi'); 
    });

    // Select Box Script
    prepareSelect(0);

});
function prepareSelect(i){
$('select:eq('+i+')').each(function(){
    var $this = $(this), numberOfOptions = $(this).children('option').length;

    $this.addClass('select-hidden');
    $this.wrap('<div class="select"></div>');
    $this.after('<div class="select-styled"></div>');

    var $styledSelect = $this.next('div.select-styled');
    $styledSelect.text($this.children('option').eq(0).text());

    var $list = $('<ul />', {
        'class': 'select-options'
    }).insertAfter($styledSelect);

    for (var i = 0; i < numberOfOptions; i++) {
        $('<li />', {
            text: $this.children('option').eq(i).text(),
            rel: $this.children('option').eq(i).val()
        }).appendTo($list);
    }

    var $listItems = $list.children('li');

    $styledSelect.click(function(e) {
        e.stopPropagation();
        $('div.select-styled.active').not(this).each(function(){
            $(this).removeClass('active').next('ul.select-options').hide();
        });
        $(this).toggleClass('active').next('ul.select-options').toggle();
    });

    $listItems.click(function(e) {
        e.stopPropagation();
        $styledSelect.text($(this).text()).removeClass('active');
        $this.val($(this).attr('rel'));
        $list.hide();
        //console.log($this.val());
    });

    $(document).click(function() {
        $styledSelect.removeClass('active');
        $list.hide();
    });

});
}
// Date Pickuer Script
// $(function() {
//     $('[data-toggle="datepicker"]').datepicker({
//         autoHide: true,
//         zIndex: 2048,
//     });
// });

$( function() {
    $( ".dateIcon" ).datepicker();
} );
