import axios from "axios";
import { useEffect, useState } from "react";
import { Link } from "react-router-dom";

export default function ListUser() {
    const [users, setUsers] = useState([]);

    // Charger les utilisateurs une fois le composant monté
    useEffect(() => {
        getUsers();
    }, []);

    // Fonction pour récupérer tous les utilisateurs
    function getUsers() {
        axios
            .get("http://localhost/Projets/PHP/userCrudWithReactAndPhp/api/index.php")
            .then((response) => {
                console.log(response.data);
                setUsers(response.data);
            })
            .catch((error) => console.error("Error fetching users:", error));
    }

    // Fonction pour supprimer un utilisateur par ID
    const deleteUser = (id) => {
        // Afficher une boîte de confirmation
        const confirmed = window.confirm(
            "Are you sure you want to delete this user? This action cannot be undone."
        );
        if (!confirmed) {
            return; // Si l'utilisateur annule, ne rien faire
        }

        // Effectuer la suppression si confirmé
        axios
            .delete(`http://localhost/Projets/PHP/userCrudWithReactAndPhp/api/index.php/${id}`)
            .then((response) => {
                console.log(response.data);
                getUsers(); // Rafraîchir la liste après suppression
            })
            .catch((error) => console.error("Error deleting user:", error));
    };

    return (
        <div className="row">
            <div className="col-12">
                <h1>List Users</h1>
                <table className="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {/* Affichage dynamique des utilisateurs */}
                        {users.map((user, key) => (
                            <tr key={key}>
                                <td>{user.id}</td>
                                <td>{user.name}</td>
                                <td>{user.email}</td>
                                <td>{user.mobile}</td>
                                <td>
                                    {/* Bouton pour éditer */}
                                    <Link 
                                        to={`/user/${user.id}/edit`} 
                                        className="btn btn-success" 
                                        style={{ marginRight: "10px" }}
                                    >
                                        Edit
                                    </Link>
                                    {/* Bouton pour supprimer */}
                                    <button 
                                        onClick={() => deleteUser(user.id)} 
                                        className="btn btn-danger"
                                    >
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </div>
    );
}
