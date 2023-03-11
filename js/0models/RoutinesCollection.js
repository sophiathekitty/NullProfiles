class RoutinesCollection extends Collection {
    constructor(debug = true){
        super("routines","routine","/plugins/NullProfiles/api/routines","/plugins/NullProfiles/api/routines/save","id","collection_", debug);
    }
    getQuickRun(callback){
        this.getData(json=>{
            callback(json.quick_run);
        });
    }
    getRoutines(callback){
        this.getData(json=>{
            callback(json.routines);
        });
    }
    runRoutine(routine_id,success,fail){
        if(this.debug) console.info("RoutinesCollection::runRoutine",routine_id);
        $.get("/plugins/NullProfiles/api/routines/start/?routine_id="+routine_id).done(json=>{
            if(this.debug) console.log("RoutinesCollection::runRoutine:success",json);
            success(json);
        }).fail(e=>{
            if(this.debug) console.error("RoutinesCollection::runRoutine:failed",e);
            fail(e);
        });
    }
}