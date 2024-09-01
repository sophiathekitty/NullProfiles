/**
 * Room Use Info View
 * for displaying room use information in room details
 */
class RoomUseInfoView extends View {
    /**
     * Constructor
     * @param {boolean} debug
     */
    constructor(debug = true){
        super(
            null,
            new Template("room_use_info","/plugins/NullProfiles/widgets/active_room_use.php"),
            null,60000,debug);
    }
    /**
     * Build
     */
    build(){
        if(this.debug) console.warn("RoomUseInfoView::Build","missing room id");
    }
    build(room_id){
        if(this.debug) console.log("RoomUseInfoView::Build",room_id);
        // still doesn't do anything?
    }
    /**
     * Display
     */
    display(){
        if(this.debug) console.warn("RoomUseInfoView::Display","missing room id");
    }
    /**
     * Display
     * @param {int} room_id 
     */
    display(room_id){
        if(this.debug) console.log("RoomUseInfoView::Display",room_id);
        // ok... so i think i just want to reload the widget...
        this.template.get_params = "?room_id="+room_id;
        this.template.getData(html=>{
            if(this.debug) console.log("RoomUseInfoView::Display",room_id,html);
            $("[room_id="+room_id+"] span.room-use-details").html(html);
        });
    }
    /**
     * Refresh
     */
    refresh(){
        if(this.debug) console.warn("RoomUseInfoView::Refresh","missing room id");
    }
}