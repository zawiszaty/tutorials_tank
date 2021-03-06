import React from 'react';
import CssBaseline from '@material-ui/core/CssBaseline';
import Paper from '@material-ui/core/Paper';
import withStyles from '@material-ui/core/styles/withStyles';
import Button from "@material-ui/core/Button/Button";

const styles = theme => ({
    avatar: {
        width: '200px',
        height: '200px',
    },
    avatar__container: {
        display: 'flex',
        justifyContent: 'center',
    }
});

class PostThumbnailForm extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            image: this.props.thumbnail
        };
    }

    handleImage = (image) => {
        this.setState({
            image: image
        })
    };

    render() {
        const classes = this.props.classes;
        return (
            <React.Fragment>
                <CssBaseline/>
                <Paper>
                    <div className={this.props.classes.avatar__container}>
                        <img src={this.state.image} className={this.props.classes.avatar}/>
                    </div>
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
                            this.props.handleChangeFile(image);
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
                </Paper>
            </React.Fragment>
        );
    }
}

export default withStyles(styles)(PostThumbnailForm);