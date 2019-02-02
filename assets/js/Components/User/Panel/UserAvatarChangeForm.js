import React from 'react'
import Button from "@material-ui/core/Button/Button";
import withStyles from '@material-ui/core/styles/withStyles';
import {connect} from 'react-redux';
import axios from "../../../axios";
import {withSnackbar} from 'notistack';
import {changeAvatar} from './../../../actions/user-action';

const styles = theme => ({
    layout: {
        width: 'auto',
        display: 'block', // Fix IE11 issue.
        marginLeft: theme.spacing.unit * 3,
        marginRight: theme.spacing.unit * 3,
        [theme.breakpoints.up(400 + theme.spacing.unit * 3 * 2)]: {
            width: 400,
            marginLeft: 'auto',
            marginRight: 'auto',
        },
    },
    paper: {
        marginTop: theme.spacing.unit * 8,
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
        padding: `${theme.spacing.unit * 2}px ${theme.spacing.unit * 3}px ${theme.spacing.unit * 3}px`,
    },
    avatar: {
        width: '200px',
        height: '200px',
        borderRadius: '100%'
    },
    form: {
        width: '100%', // Fix IE11 issue.
        marginTop: theme.spacing.unit,
    },
    submit: {
        marginTop: theme.spacing.unit * 3,
    },
});

const validate = values => {
    const errors = {};
    if (!values.username) {
        errors.username = 'Pole nie może być puste'
    }
    if (!values.password) {
        errors.password = 'Pole nie może być puste'
    }

    return errors
};

const warn = values => {
    const warnings = {};
    if (values.username < 19) {
        warnings.age = 'Hmm, you seem a bit young...'
    }
    return warnings
};


// hintText={label}
// floatingLabelText={label}
// errorText={touched && error}
class SyncValidationForm extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            onPresentSnackbar: props.onPresentSnackbar,
            user: props.user,
            image: props.user.avatar,
            uploadedImage: ''
        }
    }

    handleImage = (image) => {
        this.setState({
            image: image
        })
    };

    render() {
        return (
            <React.Fragment>
                <div>
                    <img src={this.state.image} className={this.props.classes.avatar}/>
                </div>
                <form name="changeAvatarForm" className={this.props.classes.form} onSubmit={(e) => {
                    e.preventDefault();
                    console.log(this.state.uploadedImage);

                    if (this.state.uploadedImage === '') {
                        this.state.onPresentSnackbar('error', 'To ten sam avatar')
                    } else {
                        axios.post('/api/v1/user/change/avatar', this.state.uploadedImage, {
                            headers: {'Authorization': 'Bearer ' + localStorage.getItem('token')}
                        }).then((response) => {
                            this.state.onPresentSnackbar('success', 'Zmieniono');
                            this.state.user.avatar = response.data.avatar;
                            this.setState({
                                uploadedImage: ''
                            });
                            this.props.changeAvatar(this.state.user);
                        })
                    }
                }}>
                    <input
                        accept="image/*"
                        style={{display: 'none'}}
                        id="raised-button-file"
                        multiple
                        type="file"
                        onChange={(files) => {
                            console.log(files.target.files[0]);
                            const avatar = new Blob([files.target.files[0]]);
                            let image = new FormData();
                            image.append("file", avatar, avatar.name);
                            this.setState({
                                uploadedImage: image
                            });
                            var reader = new FileReader();
                            reader.readAsDataURL(files.target.files[0]);
                            reader.onload = (e) => {
                                console.log(e);
                                this.handleImage(e.target.result);
                            }
                        }}
                    />
                    <label htmlFor="raised-button-file">
                        <Button variant="raised" component="span" fullWidth>
                            Upload
                        </Button>
                    </label>
                    <Button
                        type="submit"
                        fullWidth
                        variant="raised"
                        color="primary"
                    >
                        Zmień Avatar
                    </Button>
                </form>
            </React.Fragment>
        )
    }
}

const mapStateToProps = state => ({
    user: state.user
});

const mapActionToProps = {
    changeAvatar: changeAvatar
};

export default connect(mapStateToProps, mapActionToProps)(withStyles(styles)(withSnackbar(SyncValidationForm)))