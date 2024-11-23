import { useState, useEffect } from "react";
import axios from "axios";
import { useNavigate, useParams } from "react-router-dom";

export default function EditUser() {
    const navigate = useNavigate();
    const { id } = useParams();

    // Initialisation avec des valeurs par défaut
    const [inputs, setInputs] = useState({
        name: "",
        email: "",
        mobile: "",
    });

    useEffect(() => {
        getUser();
    }, []);

    function getUser() {
        axios
            .get(`http://localhost/Projets/PHP/userCrudWithReactAndPhp/api/index.php/${id}`)
            .then((response) => {
                console.log("Réponse API :", response.data);
                setInputs(response.data); // Assurez-vous que response.data est un objet utilisateur
            })
            .catch((error) => {
                console.error("Erreur lors de la récupération de l'utilisateur :", error);
            });
    }

    const handleChange = (event) => {
        const { name, value } = event.target;
        setInputs((values) => ({ ...values, [name]: value }));
    };

    const handleSubmit = (event) => {
        event.preventDefault();
        axios
            .put(`http://localhost/Projets/PHP/userCrudWithReactAndPhp/api/index.php/${id}`, inputs)
            .then((response) => {
                console.log("Mise à jour réussie :", response.data);
                navigate("/"); // Redirection vers la liste des utilisateurs
            })
            .catch((error) => {
                console.error("Erreur lors de la mise à jour :", error);
            });
    };

    return (
        <div className="row">
            <div className="col-2"></div>
            <div className="col-8">
                <h1>Edit User</h1>
                <form onSubmit={handleSubmit}>
                    <div className="mb-3">
                        <label>Name</label>
                        <input
                            type="text"
                            value={inputs.name || ""}
                            className="form-control"
                            name="name"
                            onChange={handleChange}
                            required
                        />
                    </div>
                    <div className="mb-3">
                        <label>Email</label>
                        <input
                            type="email"
                            value={inputs.email || ""}
                            className="form-control"
                            name="email"
                            onChange={handleChange}
                            required
                        />
                    </div>
                    <div className="mb-3">
                        <label>Mobile</label>
                        <input
                            type="text"
                            value={inputs.mobile || ""}
                            className="form-control"
                            name="mobile"
                            onChange={handleChange}
                            required
                        />
                    </div>
                    <button type="submit" className="btn btn-primary">
                        Save
                    </button>
                </form>
            </div>
            <div className="col-2"></div>
        </div>
    );
}
