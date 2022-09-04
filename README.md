# EIE3117_dating_website

This is a PolyU EIE3117 project to design a security dating website which is for making friends. Users register and visit the dating website to find suitable friends and chat with new friends.

## Designed page

The detailed page of the website will be shown as a table below:
<table>
    <thead>
        <tr>
            <th>Category</th>
            <th>Related page</th>
            <th>Function</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td rowspan=3>User Registration, Login and Logout</td>
            <td>sign_up.php</td>
            <td><ul><li>Registrar an account of the system.</li><li>Common information: login id, nick name, email, password, birthday, gender, and description</li></ul></td>
        </tr>
        <tr>
            <td>index.php</td>
            <td><ul><li>Use id or email and password to login.</li><li>Remember login status using cookie</li></ul></td>
        </tr>
        <tr>
            <td>logout.php</td>
            <td>User logout and delete cookie (if available)</td>
        </tr>
    </tbody>
    <tbody>
        <tr>
            <td rowspan=4>Core function</td>
            <td>home.php</td>
            <td>This is the home page, and the user can:<ul><li>View the messages</li><li>List all users</li></ul></td>
        </tr>
        <tr>
            <td>user_list.php</td>
            <td>Display all the users on the website</td>
        </tr>
        <tr>
            <td>view_profile.php</td>
            <td>View other user’s profile</td>
        </tr>
        <tr>
            <td>message.php</td>
            <td>Receive and send the message to the user's friends</td>
        </tr>
    </tbody>
        <tbody>
        <tr>
            <td rowspan=4>User profile</td>
            <td>profile.php</td>
            <td>User can view user profile</td>
        </tr>
        <tr>
            <td>change_password.php</td>
            <td>User can change user password</td>
        </tr>
        <tr>
            <td>upload_profile_image.php</td>
            <td>Users can upload user profile images</td>
        </tr>
        <tr>
            <td>update_description.php</td>
            <td>User can update user description</td>
        </tr>
    </tbody>
</table>

