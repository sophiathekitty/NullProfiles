class ProfileImageCollection extends Collection {
    constructor(debug = true){
        super("profile_images","image","/plugins/NullProfiles/api/images","/plugins/NullProfiles/api/images/save","id","collection_", debug);
    }
}