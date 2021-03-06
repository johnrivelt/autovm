$(function() {


    $(".vps-start").click(function(){
        var vpsId = $(this).data("id");

        $.ajax({
            url:baseUrl + "/site/vps/start",
            type:"POST",
            dataType:"JSON",
            data:{vpsId:vpsId},
            success:function(data){
                if(data.status == 1) {
                    new simpleAlert({title:"Action Status", content:"Your vps was successfuly started"});
                } else {
                    new simpleAlert({title:"Action Status", content:"There is an error, please try again"});
                }
            },
            beforeSend:function() {
                new simpleAlert({title:"Starting", content:"Please wait a moment..."});
            }
        });
    });

    $(".vps-stop").click(function(){
        var vpsId = $(this).data("id");

        $.ajax({
            url:baseUrl + "/site/vps/stop",
            type:"POST",
            dataType:"JSON",
            data:{vpsId:vpsId},
            success:function(data){
                if(data.status == 1) {
                    new simpleAlert({title:"Action Status", content:"Your vps was successfuly stopped"});
                } else {
                    new simpleAlert({title:"Action Status", content:"There is an error, please try again"});
                }
            },
            beforeSend:function() {
                new simpleAlert({title:"Stopping", content:"Please wait a moment..."});
            }
        });
    });

    $(".vps-restart").click(function(e){
        e.preventDefault();

        var vpsId = $(this).data("id");

        $.ajax({
            url:baseUrl + "/site/vps/restart",
            type:'POST',
            dataType:'JSON',
            data:{vpsId:vpsId},
            success:function(data){
                if(data.status == 1) {
                    new simpleAlert({title:'Action Status', content:'Your vps was successfuly restarted'});
                } else {
                    new simpleAlert({title:'Action Status', content:'There is an error, please try again'});
                }
            },
            beforeSend:function() {
                new simpleAlert({title:'Restarting', content:'Please wait a moment...'});
            }
        });
    });

    $(".vps-change-os").click(function(e){
        e.preventDefault();

        var vpsId = $(this).data("id");

        $.ajax({
            url:baseUrl + "/site/vps/select-os",
            type:'POST',
            dataType:'HTML',
            data:{vpsId:vpsId},
            success:function(data){
                new simpleAlert({title:'Select Operation System', content:data});
            },
            beforeSend:function() {
                new simpleAlert({title:'Loading', content:'Please wait a moment...'});
            }
        });
    });

    $(".vps-status").click(function(e){
        e.preventDefault();

        var vpsId = $(this).data("id");

        $.ajax({
            url:baseUrl + "/site/vps/status",
            type:'POST',
            dataType:'JSON',
            data:{vpsId:vpsId},
            success:function(data){
                if(data.status == 1) {
                    new simpleAlert({title:'Action Status', content:'Your vps is online'});
                } else if (data.status == 2) {
                    new simpleAlert({title:'Action Status', content:'Your vps is offline'});
                } else {
                    new simpleAlert({title:'Action Status', content:'There is an error, please try again'});
                }
            },
            beforeSend:function() {
                new simpleAlert({title:'Loading', content:'Please wait a moment...'});
            }
        });
    });

    $(".vps-action-log").click(function(e){
       e.preventDefault()

       var vpsId = $(this).data("id");

       $.ajax({
          url:baseUrl + "/site/vps/action-log",
          type:'POST',
          dataType:'HTML',
          data:{vpsId:vpsId},
          success:function(data) {
            new simpleAlert({title:'Action Logs', content:data});
          },
          beforeSend:function() {
            new simpleAlert({title:'Loading', content:'Please wait a moment...'});
          }
       });
    });
    
    $(".vps-monitor").click(function(e){
       e.preventDefault()

       var vpsId = $(this).data("id");

       $.ajax({
          url:baseUrl + "/site/vps/monitor",
          type:'POST',
          dataType:'HTML',
          data:{vpsId:vpsId},
          success:function(data) {
            if (data == "") {
                new simpleAlert({title:'Action Status', content:'There is an error, please try again'});
            } else {
                new simpleAlert({title:'Monitor', content:data});
            }
          },
          beforeSend:function() {
            new simpleAlert({title:'Loading', content:'Please wait a moment...'});
          }
       });
    });
});