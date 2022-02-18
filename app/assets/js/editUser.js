import { createNotificationType } from "./general";
import { createPreviewById } from "./imagePreview";
import notification from "./components/notification";

createPreviewById("user_edit_photo")
createNotificationType('user_create_dealScan', 'change', 'Success', 'Deal scan was successfully uploaded', notification.data().success)
createNotificationType('user_edit_dealScan', 'change', 'Success', 'Deal scan was successfully uploaded', notification.data().success)
