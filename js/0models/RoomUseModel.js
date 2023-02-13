class RoomUseModel extends Model {
    constructor(debug = true){
        super("profile","/plugins/NullProfiles/api/rooms/current","/plugins/NullProfiles/api/rooms",0,"model_", debug);
    }
    /**
     * gets the current room use
     * @param {int} room_id 
     * @param {function(JSON)} callBack 
     */
    roomUse(room_id,callBack){
        this.get_params = "?room_id="+room_id;
        if(this.debug) console.info("RoomUseModel::roomUse - room_id:",room_id,this.get_url+this.get_params)
        this.pullData(json=>{
            if(this.debug) console.log("RoomUseModel::roomUse - json:",json);
            callBack(json);
        });
    }
}