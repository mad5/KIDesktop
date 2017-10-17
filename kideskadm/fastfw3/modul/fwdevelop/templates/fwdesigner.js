var fwdesigner =  {
    "screens": [],
    "maxzindex": 1000,
    "init": function() {

        for(var i=0;i<this.screens.length;i++) {
            this.screens[i].zindex *= 1;
            this.screens[i].x *= 1;
            this.screens[i].y *= 1;

            if(!this.screens[i].views) this.screens[i].views = [];
            for(var j=0;j<this.screens[i].views.length;j++) {
                this.screens[i].views[j].zindex *= 1;
                this.screens[i].views[j].x *= 1;
                this.screens[i].views[j].y *= 1;
            }
        }

        this.rebuild();
    },
    "saveScreens": function() {
        // Todo
        $.ajax({
            "url": "<?= getAjaxLink('*/save');?>",
            "type": "post",
            "data": {"screens": this.screens},
            "dataType": "json",
            "success": function(data) {

            }
        });
    },
    "rebuild": function() {
        $(".fd_title").unbind(".screenevent");

        $(".fwd_screens").remove();
        for(var i=0;i<this.screens.length;i++) {
            if(this.screens[i].zindex>this.maxzindex) this.maxzindex = this.screens[i].zindex;
            var html = $('#screentpl').html();
            html = html.replace("##sid##", this.screens[i].id);
            while(html.indexOf("##nr##")!=-1) { html = html.replace("##nr##", i); }
            var obj = $(html);
            obj.find(".fd_title").html(this.screens[i].title);
            obj.css("left", this.screens[i].x);
            obj.css("top", this.screens[i].y);

            var v = "";
            for(var j=0;j<this.screens[i].views.length;j++) {
                v += "<div class='viewtitles'>"+this.screens[i].views[j].title+"</div>"
            }
            obj.find(".fd_info").html(v);
            $("body").append(obj);
        }

        var $this = this;

        $(".fd_title").bind("mousedown.screenevent", function(e) {

            e.preventDefault();
            var startX = e.pageX;
            var startY = e.pageY;
            var nr = $(this).attr("rel")*1;


            $(window).bind("mousemove.screenevent", function(e) {
                e.preventDefault();
                var nowX = e.pageX;
                var nowY = e.pageY;

                $this.screens[nr].x += nowX-startX;
                $this.screens[nr].y += nowY-startY;

                for(var i=0;i<$this.screens[nr].views.length;i++) {
                    $this.screens[nr].views[i].x += nowX-startX;
                    $this.screens[nr].views[i].y += nowY-startY;
                }


                $("#"+$this.screens[nr].id).css("left", $this.screens[nr].x).css("top", $this.screens[nr].y);

                startX = nowX;
                startY = nowY;
            });

            $(window).bind("mouseup.screenevent", function(e) {
                $(window).unbind(".screenevent");
                $this.saveScreens();
            });
        });
    },
    "editScreenTitle": function(nr) {
        var P = prompt("Titel", this.screens[nr].title);
        if(P!=false) {
            this.screens[nr].title = P;
            this.rebuild();
            this.saveScreens();
        }
    },
    "editViewTitle": function(nr) {
        var P = prompt("Titel", this.screens[this.activeScreen].views[nr].title);
        if(P!=false) {
            this.screens[this.activeScreen].views[nr].title = P;
            this.rebuildViews();
            this.saveScreens();
        }
    },

    "addScreen": function() {
        var S = {
                "id": "fwds_"+this.screens.length,
                "title": "new Screen",
                "info": "",
                "x": $(window).width()/2,
                "y": $(window).height()/2,
                "zindex": ++this.maxzindex,
                "views": []
            };
        this.screens.push(S);
        this.rebuild();
    },
    "activeScreen": -1,
    "addView": function() {
        if(this.activeScreen==-1) return;
        var V = {
            "id": "fwdv_"+this.screens[this.activeScreen].views.length,
            "title": "new View",
            "info": "",
            "x": $(window).width()/2,
            "y": $(window).height()/2,
            "zindex": ++this.maxzindex
        };
        this.screens[this.activeScreen].views.push(V);
        this.rebuildViews();
    },
    "closeViews": function() {
        $('#viewpanel').remove();
        this.activeScreen = -1;
        this.rebuild();
    },
    "openViews": function(nr) {
        this.activeScreen = nr;
        var html = "<div id='viewpanel' style='position:absolute;left:0;top:0;width:"+$(window).width()+"px;height:"+$(window).height()+"px;background-color:rgba(200,200,200,0.5);'></div>";
        $("body").append(html);
        this.rebuildViews();

    },
    "rebuildViews": function() {

        $('#viewpanel').find(".fwd_views").remove();

        for(var i=0;i<this.screens[this.activeScreen].views.length;i++) {
            var html = $('#viewtpl').html();
            html = html.replace("##vid##", this.screens[this.activeScreen].views[i].id);
            while(html.indexOf("##vnr##")!=-1) { html = html.replace("##vnr##", i); }
            var obj = $(html);
            obj.find(".fdv_title").html(this.screens[this.activeScreen].views[i].title);
            obj.css("left", this.screens[this.activeScreen].views[i].x);
            obj.css("top", this.screens[this.activeScreen].views[i].y);
            $("#viewpanel").append(obj);
        }


        var $this = this;

        $(".fdv_title").bind("mousedown.screenevent", function(e) {

            e.preventDefault();
            var startX = e.pageX;
            var startY = e.pageY;
            var nr = $(this).attr("rel")*1;


            $(window).bind("mousemove.screenevent", function(e) {
                e.preventDefault();
                var nowX = e.pageX;
                var nowY = e.pageY;

                $this.screens[$this.activeScreen].views[nr].x += nowX-startX;
                $this.screens[$this.activeScreen].views[nr].y += nowY-startY;

                $("#"+$this.screens[$this.activeScreen].views[nr].id).css("left", $this.screens[$this.activeScreen].views[nr].x).css("top", $this.screens[$this.activeScreen].views[nr].y);

                startX = nowX;
                startY = nowY;
            });

            $(window).bind("mouseup.screenevent", function(e) {
                $(window).unbind(".screenevent");
                $this.saveScreens();
            });
        });

    }
};