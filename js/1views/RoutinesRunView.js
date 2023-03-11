class RoutinesRunView extends View {
    constructor(debug = true){
        super(
            new RoutinesCollection(),
            new Template("run_routines_list","/plugins/NullProfiles/widgets/run_routines_list.php"),
            new Template("quick_run_routine","/plugins/NullProfiles/widgets/quick_run_routine.php"),60000,debug);
        
    }
    build(){
        if(this.debug) console.info("RoutineRunView::Build");
        $("<nav class='routines'></nav>").appendTo("footer");
        this.display();
    }
    display(){
        if(this.debug) console.info("RoutineRunView::Display");
        this.item_template.getData(html=>{
            $("footer nav.routines").html(html);
        });
    }
    refresh(){
        if(this.debug) console.info("RoutineRunView::Refresh");
        this.display();
    }
    showRoutinesList(){
        if(this.debug) console.info("RoutineRunView::ShowRoutineList");
        this.template.getData(html=>{
            if(this.debug) console.info("RoutineRunView::ShowRoutineList ----------?",html);
            $(html).appendTo("body");
            var dialog = document.getElementById('routines_run_list');
            dialog.showModal();
            dialog.blur();
        });
    }
    hideRoutinesList(){
        if(this.debug) console.info("RoutineRunView::HideRoutineList");
        $("dialog#routines_run_list").remove();
        this.refresh();
    }
}