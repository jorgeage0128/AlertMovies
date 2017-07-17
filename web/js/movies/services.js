actorPages = 1;
currentActorPage = 1;

$(document).ready(function() {
    $('#example').DataTable({
        pagingType: "simple",
        searching: false,
        pageLength: 20,
        ordering: false
    });

    $('#movieListTable').DataTable({
        pageLength: 20,
        ordering: false,
        pagingType: "simple",
    });

    $('#movieList').on('hidden.bs.modal', function () {
        clean();
    });

    $('#example').removeClass( 'display' ).addClass('table table-striped table-bordered');
});

$('#actor').keypress(function (e) {
    var key = e.which;
    if(key == 13)  // the enter key code
    {
        firstCall();
    }
});

function firstCall(){
    actorPages = 1;
    currentActorPage = 1;
    if ($("#actor").val() != ""){
        getActors();
        $("#alertMsg").css("display","none");
    } else {
        $("#alertMsg").css("display","block");
    }

}

function cleanScreen(){
    actorPages = 1;
    currentActorPage = 1;
    $('#actor').val("");
    $("#dataTable").css("display","none");


}

function clean(){
    var table = $('#movieListTable').DataTable();
    table.clear();
    table.draw();
}

function prepareMessageModal(titleContent, bodyContent){
    $('#modalTitle').html(titleContent);
    $('#modalBody').html(bodyContent);
}

function getActors(){
    $.ajax({
        type:"GET",
        url:"/api/actor",
        dataType:"json",
        data: {
            query: $("#actor").val(),
            page : currentActorPage
        },
        beforeSend: function(xhr){
            prepareMessageModal("Loading","<img src='http://www.vaporizerviews.com/wp-content/plugins/no-spam-at-all/img/loading.gif' />")
            $('#modal').modal('show');
        }
    }).done(function (data) {
        if (data.results.length > 0){
            processData(data.results, data.total_results, data.total_pages);
            $('#modal').modal('hide');
        } else {
            prepareMessageModal("Error","Actors Not Found");
            $("#dataTable").css("display","none");
        }
    });
}

function getMovies(actorId){
    $('#movieList').modal('show');

    $.ajax({
        type:"GET",
        url:"/api/actorMovies",
        dataType:"json",
        data: {
            actorID: actorId
        },
        beforeSend: function(xhr){
            prepareMessageModal("Loading","<img src='http://www.vaporizerviews.com/wp-content/plugins/no-spam-at-all/img/loading.gif' />")
            $('#modal').modal('show');
        }
    }).done(function (data) {
        processMovieData(data);
        $('#modal').modal('hide');
    });

}

function processMovieData(data){
    var table = $('#movieListTable').DataTable();
    if (data.length > 0){
        table.clear();
        table.draw();
        $.each(data, function(key, value){
            table.row.add([
                    value.movie+"&emsp;",value.date==""? "No Date":value.date
                ]
            );
        });
        table.draw();
        $("#movieListTable_length").css("display","none");

    } else {
        table.clear();
        table.draw();
    }
}

function processData(data, totalRows, totalPages){
    if (data != ""){
        $("#dataTable").css("display","block");
        var table = $('#example').DataTable();
        table.clear();
        $.each(data, function(key, value){
            table.row.add([
                    value.name, "<button class='btn btn-default' onclick=getMovies('"+value.id+"')>See Movies</button>"
            ]
            );
        });
        table.draw();

        if (totalRows < 20){
            endPage =  totalRows;
            startPage = 1;
        } else {
            endPage = currentActorPage * 20;
            startPage = (endPage - 20) + 1;
        }

        $("#example_length").css("display","none");

        $("#example_info").html("Showing "+startPage+" to "+endPage+" of "+totalRows+" entries");

        if (totalRows > 20 && currentActorPage < totalPages){
            $("#example_next").removeClass("disabled");
        } else {
            $("#example_next").addClass("disabled");
            $( "body" ).off( "click", "#example_next:has(a)", function(){} );
        }

        if (currentActorPage > 1){
            $("#example_previous").removeClass("disabled");
        } else {
            $("#example_previous").addClass("disabled");
            $( "body" ).off( "click", "#example_previous:has(a)", function(){} );
        }

        $("#example_next:has(a)").click(function(){
            currentActorPage++;
            getActors();
        });

        $("#example_previous:has(a)").click(function(){
            currentActorPage--;
            getActors();
        });
    }
}
