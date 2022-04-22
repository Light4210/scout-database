import { createNotificationType } from "./general";
import { createPreviewById } from "./imagePreview";
import notification from "./components/notification";

createPreviewById("register_photo")
createNotificationType('register_dealScan', 'change', 'Success', 'Deal scan was successfully uploaded', notification.data().success)
createNotificationType('register_dealScan', 'change', 'Success', 'Deal scan was successfully uploaded', notification.data().success)
