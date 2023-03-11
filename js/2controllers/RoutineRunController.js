/**
 * routine run controller
 */
class RoutineRunController extends Controller {
    static instance = new RoutineRunController();
    constructor(debug = true){
        if(debug) console.info("RoutineRunController::Constructor");
        super(new RoutinesRunView(debug),debug);
    }
    /**
     * setup routine run event handling
     */
    ready(){
        if(this.debug) console.info("RoutineRunController::Ready");
        this.view.build();
        this.refreshInterval();
        this.listenForEvent("mousedown","footer","nav.routines a[action=run]",this.routinesMouseDownHandler.bind(this));
        this.click("footer","nav.routines a[action=list]",this.showRoutinesListClick.bind(this));
        this.click("body","dialog nav.routines a[action=cancel]",this.hideRoutinesListClick.bind(this));
        this.click("body","dialog nav.routines a[action=run]",this.runRoutineClick.bind(this));
        
    }
    /**
     * handle mouse down. so we can long hold to show the routines list
     * @param {MouseEvent} e the mouse event object
     */
    routinesMouseDownHandler(e){
        if(this.debug) console.info("RoutineRunController::routinesMouseDownHandler",e);
        e.preventDefault();
        this.showListTimeout = setTimeout(this.showRoutinesList.bind(this),3000);
        this.listenForEvent("mouseup","footer","nav.routines a[action=run]",this.routinesMouseUpHandler.bind(this));
        this.listenForEvent("mouseout","footer","nav.routines a[action=run]",this.routinesMouseOutHandler.bind(this));
        this.showingList = false;
    }
    /**
     * handle the mouse up. run the routine
     * @param {MouseEvent} e the mouse event object
     */
    routinesMouseUpHandler(e){
        if(this.debug) console.info("RoutineRunController::routinesMouseUpHandler",e);
        e.preventDefault();
        clearTimeout(this.showListTimeout);
        this.removeListenerForEvent("mouseup","footer","nav.routines a[action=run]");
        this.removeListenerForEvent("mouseout","footer","nav.routines a[action=run]");
        // get the routine id and run the routine
        var routine_id = $(e.target).attr("routine_id");
        if(this.debug) console.log("RoutineRunController::routinesMouseUpHandler::routine_id",routine_id);
        this.view.model.runRoutine(routine_id,this.routineRunSuccess.bind(this),this.routineRunFailed.bind(this));
    }
    /**
     * handle the mouse up. run the routine
     * @param {MouseEvent} e the mouse event object
     */
    runRoutineClick(e){
        if(this.debug) console.info("RoutineRunController::runRoutineClick",e);
        e.preventDefault();
        var routine_id = $(e.target).attr("routine_id");
        if(this.debug) console.log("RoutineRunController::runRoutineClick::routine_id",routine_id);
        this.view.model.runRoutine(routine_id,this.routineRunSuccess.bind(this),this.routineRunFailed.bind(this));
    }
    /**
     * routine run success
     */
    routineRunSuccess(json){
        if(this.debug) console.info("RoutineRunController::routineRunSuccess",json);
        this.view.hideRoutinesList();
    }
    /**
     * routine run failed
     */
    routineRunFailed(error){
        if(this.debug) console.info("RoutineRunController::routineRunFailed",error);

    }
    /**
     * never mind showing the list or whatever 
     * @param {MouseEvent} e the mouse event object
     */
    routinesMouseOutHandler(e){
        if(this.debug) console.info("RoutineRunController::routinesMouseOutHandler",e);
        e.preventDefault();
        clearTimeout(this.showListTimeout);
        this.removeListenerForEvent("mouseup","footer","nav.routines a[action=run]");
        this.removeListenerForEvent("mouseout","footer","nav.routines a[action=run]");
    }
    /**
     * click the routines list button to show the routines list
     * @param {MouseEvent} e the mouse event object
     */
    showRoutinesListClick(e){
        if(this.debug) console.info("RoutineRunController::showRoutinesListClick",e);
        e.preventDefault();
        this.showRoutinesList();
    }
    /**
     * click the close button to hide the routines list
     * @param {MouseEvent} e the mouse event object
     */
    hideRoutinesListClick(e){
        if(this.debug) console.info("RoutineRunController::hideRoutinesListClick",e);
        e.preventDefault();
        this.view.hideRoutinesList();
    }
    showRoutinesList(){
        if(this.showingList) return;
        this.showingList = true;
        if(this.debug) console.info("RoutineRunController::showRoutinesList ----?");
        clearTimeout(this.showListTimeout);
        this.removeListenerForEvent("mouseup","footer","nav.routines a[action=run]");
        this.removeListenerForEvent("mouseout","footer","nav.routines a[action=run]");
        this.view.showRoutinesList();
    }
    refresh(){
        if(this.debug) console.info("RoutineRunController::Refresh");
        this.view.refresh();
    }
}