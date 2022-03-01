class ProfileView extends View {
    constructor(debug = true){
        super(
            new ProfileModel(),
            new Template("profile","/plugins/NullProfiles/templates/profile.html"),
            new Template("profile","/plugins/NullProfiles/templates/items/pronouns.html"),60000,debug);
        
    }
}