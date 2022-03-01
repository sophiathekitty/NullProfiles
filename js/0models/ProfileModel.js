class ProfileModel extends Model {
    constructor(debug = true){
        super("profile","/plugins/NullProfiles/api/user","/plugins/NullProfiles/api/user/save",0,"model_", debug);
    }
}