import { createNotificationType } from "./general";
import notification from "./components/notification";

createNotificationType('user_create_dealScan', 'change', 'Success', 'Deal scan was successfully uploaded', notification.data().success)
createNotificationType('user_edit_dealScan', 'change', 'Success', 'Deal scan was successfully uploaded', notification.data().success)
