class RoomUseView extends View {
    constructor(debug = true){
        super(
            new RoomUseModel(),
            null,
            null,60000,debug);
    }
    build(){
        if(this.debug) console.warn("RoomUseView::Build","missing room id");
    }
    display(){
        if(this.debug) console.warn("RoomUseView::Display","missing room id");
    }
    refresh(){
        if(this.debug) console.warn("RoomUseView::Refresh","missing room id");
    }
    build(room_id){
        if(this.debug) console.log("RoomUseView::Build",room_id);
        
    }
    display(room_id){
        if(this.debug) console.info("RoomUseView::Display",room_id);

        this.model.roomUse(room_id,json=>{
            if(this.debug) console.info("RoomUseView::Display",room_id,"use",json);
            if(json.room_use != null){
                if(this.debug) console.log("RoomUseView::Display",room_id,json.room_use);
                if($("[room_id="+room_id+"] .sensors .room_uses").html() == ""){
                    $("<span class='room_use'></span>").appendTo("[room_id="+room_id+"] .room_uses");
                }
                $("[room_id="+room_id+"] .room_use").attr("type",json.room_use.type);
                $("[room_id="+room_id+"] .room_use").css("background-image","url(/plugins/NullProfiles/img/rooms/"+json.room_use.type+".png)");
                var tool_tip = json.room_use.title+"\nlight level: "+json.room_use.light_level;
                if(json.room_use.light_min != null) tool_tip += "\nlight min: "+json.room_use.light_min;
                if(json.room_use.light_max != null) tool_tip += "\nlight max: "+json.room_use.light_max;
                if(json.room_use.light_end != null) tool_tip += "\nlight end: "+json.room_use.light_end;
                $("[room_id="+room_id+"] .room_use").attr("title",tool_tip);

            } else {
                if(this.debug) console.log("RoomUseView::Display",room_id,json.room_use);
                $("[room_id="+room_id+"] .room_uses").html("");
            }
        });
    }
    refresh(room_id){
        if(this.debug) console.info("RoomUseView::Refresh",room_id);
        this.display(room_id);
    }
}