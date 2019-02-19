import React from 'react';
import Button from '@material-ui/core/Button';
import {ValidatorForm, TextValidator} from 'react-material-ui-form-validator';
import FormControl from "../../containers/Registtration/Registration";
import withStyles from "@material-ui/core/es/styles/withStyles";
import CircularProgress from "@material-ui/core/CircularProgress";
import classNames from 'classnames';
import green from '@material-ui/core/colors/green';
import axios from './../../axios/axios';
import Redirect from "react-router-dom/es/Redirect";
import {ToastContainer, toast} from 'react-toastify';
import {client_id, client_secret} from "./../../axios/env";
import {login} from "../../actions/user";
import {connect} from "react-redux";
import TableCell from "@material-ui/core/TableCell";

const styles = theme => ({
    form: {
        width: '100%', // Fix IE 11 issue.
        marginTop: theme.spacing.unit,
    },
    wrapper: {
        margin: theme.spacing.unit,
        position: 'relative',
        display: 'flex',
        alignItems: 'center',
    },
    buttonSuccess: {
        backgroundColor: green[500],
        '&:hover': {
            backgroundColor: green[700],
        },
    },
    buttonProgress: {
        color: green[500],
        position: 'absolute',
        top: '50%',
        left: '50%',
        marginTop: -12,
        marginLeft: -12,
    },
});

class BanButton extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            ban: props.n.banned,
            roles: props.n.roles
        }
    }

    render() {
        let {n} = this.props;
        let {roles} = this.state;
        return (
            <React.Fragment>
                <TableCell component="th" scope="row" padding="default">
                    {this.state.ban !== true ?
                        <Button
                            variant="contained"
                            color="secondary"
                            type="submit"
                            onClick={(event) => {
                                event.stopPropagation();
                                axios.patch(`/api/v1/user/banned/${n.id}`, {}, {
                                    headers: {'Authorization': 'Bearer ' + localStorage.getItem('token')}
                                })
                                    .then((response) => {
                                        toast.success("Pomyślnie zbanowano", {
                                            position: toast.POSITION.BOTTOM_RIGHT
                                        });
                                        this.setState({
                                            ban: true
                                        })
                                    })
                            }
                            }
                        >
                            Zbanuj
                        </Button> :
                        <Button
                            variant="contained"
                            color="default"
                            type="submit"
                            onClick={(event) => {
                                event.stopPropagation();
                                axios.patch(`/api/v1/user/banned/${n.id}/un`, {}, {
                                    headers: {'Authorization': 'Bearer ' + localStorage.getItem('token')}
                                })
                                    .then((response) => {
                                        toast.success("Pomyślnie odbanowano", {
                                            position: toast.POSITION.BOTTOM_RIGHT
                                        });
                                        this.setState({
                                            ban: false
                                        })
                                    })
                            }
                            }
                        >
                            Odbanuj
                        </Button>
                    }
                </TableCell>
                <TableCell component="th" scope="row" padding="default">
                    {roles.includes('ROLE_ADMIN') !== true ?
                        <React.Fragment>
                            {this.state.ban !== true &&
                            <Button
                                variant="contained"
                                color="primary"
                                type="submit"
                                onClick={(event) => {
                                    event.stopPropagation();
                                    axios.patch(`/api/v1/user/role/admin/${n.id}`, {}, {
                                        headers: {'Authorization': 'Bearer ' + localStorage.getItem('token')}
                                    })
                                        .then((response) => {
                                            toast.success("Pomyślnie nadano", {
                                                position: toast.POSITION.BOTTOM_RIGHT
                                            });
                                            let roles = 'a:2:{i:0;s:9:"ROLE_USER";i:1;s:10:"ROLE_ADMIN";}';
                                            this.setState({roles});
                                        })
                                }
                                }
                            >
                                Nadaj admina
                            </Button>
                            }
                        </React.Fragment> :
                        <React.Fragment>
                            {this.state.ban !== true &&
                            <Button
                                variant="contained"
                                color="default"
                                type="submit"
                                onClick={(event) => {
                                    event.stopPropagation();
                                    axios.patch(`/api/v1/user/role/admin/${n.id}/un`, {}, {
                                        headers: {'Authorization': 'Bearer ' + localStorage.getItem('token')}
                                    })
                                        .then((response) => {
                                            toast.success("Pomyślnie zabrano", {
                                                position: toast.POSITION.BOTTOM_RIGHT
                                            });
                                            let roles = 'a:1:{i:0;s:9:"ROLE_USER";}';
                                            this.setState({roles});
                                        })
                                }
                                }
                            >
                                Zabierz admina
                            </Button>
                            }
                        </React.Fragment>
                    }
                </TableCell>
            </React.Fragment>
        )
    }
}

const mapStateToProps = (state) => {
    return {
        user: state.user
    }
};
const mapDispatchToProps = {login};

export default connect(mapStateToProps, mapDispatchToProps)(withStyles(styles)(BanButton));
